<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Channel\ChannelEnum;
use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Enums\Ticket\TicketStateEnum;
use App\Jobs\Bot\AnswerToNewMessage;
use App\Models\Channel\Channel;
use App\Models\Channel\Order;
use App\Models\Ticket\Message;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use Cnsi\Lock\Lock;
use Cnsi\Logger\Logger;
use DateTime;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\DB;


class RakutenImportMessages extends AbstractImportMessages
{
    private Client $client;
    const FROM_DATE_TRANSFORMATOR = ' - 2 hour';
    const PAGE ='sales_ws';
    const GET_ITEM_TODO_LIST ='getitemtodolist';
    const GET_ITEM_TODO_LIST_VERSION = '2011-09-01';
    const GET_ITEM_INFOS = 'getiteminfos';
    const GET_ITEM_INFOS_VERSION = '2017-08-07';
    const ALERT_LOCKED_SINCE = 600;
    const KILL_LOCKED_SINCE = 1200;

    public function __construct()
    {
        $this->signature = sprintf($this->signature, 'rakuten');
        parent::__construct();
    }

    protected function getCredentials(): array
    {
        return [
            'host' => env('RAKUTEN_API_URL'),
            'login' => env('RAKUTEN_LOGIN'),
            'password' => env('RAKUTEN_PASSWORD'),
            'token' => env('RAKUTEN_TOKEN')
        ];
    }

    protected function initApiClient(): Client
    {
        $client = new Client([
            'token' => self::getCredentials()['token'],
        ]);

        $this->client = $client;
        return $this->client;
    }

    /**
     * @throws Exception
     */
    protected function convertApiResponseToMessage(Ticket $ticket, $messageApi, Thread $thread, $attachments = [])
    {
        $authorType = $messageApi['MpCustomerId'];
        $this->logger->info('Set ticket\'s status to waiting admin');
        $ticket->state = TicketStateEnum::OPENED;
        $ticket->save();
        $this->logger->info('Ticket save');
        $message = Message::firstOrCreate([
            'thread_id' => $thread->id,
            'channel_message_number' => $messageApi['id'],
        ],
            [
                'user_id' => null,
                'author_type' =>
                    $authorType == 'Rakuten'
                        ? TicketMessageAuthorTypeEnum::OPERATOR
                        : TicketMessageAuthorTypeEnum::CUSTOMER,
                'content' => strip_tags($messageApi['Message']),
            ],
        );

        // Dispatch the job that will try to answer automatically to this new imported
        AnswerToNewMessage::dispatch($message);
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    public function handle()
    {
        $lock = new Lock($this->getName(), self::ALERT_LOCKED_SINCE, self::KILL_LOCKED_SINCE, env('ERROR_RECIPIENTS'));
        $lock->lock();

        $this->channel = Channel::getByName(ChannelEnum::RAKUTEN_COM);
        $this->logger = new Logger(
            'import_message/'
            . $this->channel->getSnakeName() . '/'
            . $this->channel->getSnakeName()
            . '.log', true, true
        );
        $this->logger->info('--- Start ---');

        // GET LAST MESSAGES
        $this->logger->info('Init api');
        $this->initApiClient();

        $fromTime = strtotime(date('Y-m-d H:m:s') . self::FROM_DATE_TRANSFORMATOR);
        $fromDate = date('Y-m-d H:i:s', $fromTime);

        //get item list
        $items = $this->getItems();

        //get infos
        $threadList = $this->getInfos($items);
        $threads = $this->sortMessagesByDate($threadList);

        try {
            DB::beginTransaction();
            foreach($threads as $messages) {
                $this->logger->info('Begin Transaction');


                if(isset($messages[0])){
                $order  = Order::getOrder($messages[0]['MpOrderId'], $this->channel);
                $ticket = Ticket::getTicket($order, $this->channel);
                $thread = Thread::getOrCreateThread($ticket, $messages[0]['MpItemId'], $messages[0]['type']);
                $this->importMessageByThread($ticket, $thread, $messages);
                }
            }
            DB::commit();
        } catch (Exception $e) {
            $this->logger->error('An error has occurred. Rolling back.', $e);
            DB::rollBack();
                \App\Mail\Exception::sendErrorMail($e, $this->getName(), $this->description, $this->output);
            return;
        }

        $this->logger->info('Get messages');
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    private function getItems(): array
    {
        $this->logger->info('Get thread list');

        $response = $this->client->request(
                'GET', $this->getCredentials()['host'] . '/'. self::PAGE
                . '?action='  . self::GET_ITEM_TODO_LIST
                . '&login='   . env('RAKUTEN_LOGIN')
                . '&pwd='     . env('RAKUTEN_PASSWORD')
                . '&version=' . self::GET_ITEM_TODO_LIST_VERSION
            );

        if($response->getStatusCode() != '200')
            throw new Exception('getitemtodolist api request gone bad');

        $items = $response->getBody()->getContents();
        return $this->xmlItemsToArray($items);
    }

    private function xmlItemsToArray($response): array
    {
        $messages = array();

        if (strlen($response) > 0) {
            $data = simplexml_load_string($response); //data is a SimpleXMLElement

            if ($data->response) {
                $msgs = $data->response->items->item;

                $nbMsgs = count($msgs);

                if ($nbMsgs > 0) {
                    foreach ($msgs as $msg) {
                        $msgId = (string)$msg->itemid;
                        $cause = (string)$msg->causes->cause;
                        $messages[$msgId] = $cause;
                    }
                }
            }
        }

        return $messages;
    }

    private function xmlThreadToArray($xml): array
    {
        $messages = [];

        if (strlen($xml) > 0) {
            $data = simplexml_load_string($xml); //data is a SimpleXMLElement

            $res = $data->response;
            if ($res) {
                $MpOrderId = (string)$res->purchaseid;

                $item = $res->item;

                if (!empty($item)) {
                    $MpItemId = (string)$item->itemid;

                    $message = [];
                    if (!empty($item->message)) {
                        foreach ($item->message as $mess) {
                            $message['MpOrderId'] = $MpOrderId;
                            $message['MpItemId'] = $MpItemId;
                            $message['MpCustomerId'] = (string)$mess->sender;
                            $message['Recipient'] = (string)$mess->recipient;
                            $message['Date'] = (string)$mess->senddate;
                            $message['Message'] = trim($this->removeCdata($mess->content));
                            $message['Status'] = (string)$mess->status;
                            if ($message['MpCustomerId'] != 'Icoza') {
                                $messages[] = $message;//get only message from customer (don't re import our own answer)
                            }
                        }
                    }

                    if (!empty($item->mail)) {
                        foreach ($item->mail as $mail) {
                            $message['MpOrderId'] = $MpOrderId;
                            $message['MpItemId'] = $MpItemId;
                            $message['MpCustomerId'] = (string)$mail->sender;
                            $message['Recipient'] = (string)$mail->recipient;
                            $message['Date'] = (string)$mail->senddate;
                            $message['Object'] = trim($this->removeCdata($mail->object));
                            $message['Message'] = trim($this->removeCdata($mail->content));
                            $message['Status'] = (string)$mail->status;
                            if ($message['MpCustomerId'] !='Icoza') {
                                $messages[] = $message;//get only message from customer (don't re import our own answer)
                            }
                        }
                    }
                }
            }
        }

        return $messages;
    }

    /**
     * @throws Exception
     * @throws GuzzleException
     */
    private function getInfos($msgsId): array
    {
        $this->logger->info('Get messages list');
        $arrayMessages = [];
        foreach ($msgsId as $msgId => $type) {
            $response = $this->client->request(
                'GET', $this->getCredentials()['host'] . '/' . self::PAGE
                . '?action='  . self::GET_ITEM_INFOS
                . '&login='   . env('RAKUTEN_LOGIN')
                . '&pwd='     . env('RAKUTEN_PASSWORD')
                . '&version=' . self::GET_ITEM_INFOS_VERSION
                . '&itemid='  . $msgId
            );

            if($response->getStatusCode() != '200')
                throw new Exception('getiteminfos api request gone bad');

            $messages = $response->getBody()->getContents();

            $messagesList = $this->xmlThreadToArray($messages);
            if (isset($messagesList[0]))
                foreach($messagesList as &$msg){
                    $msg['type'] = $type;
                }

            $arrayMessages[] = $messagesList;
        }

        return $arrayMessages;
    }

    protected function removeCdata($fieldString): array|string
    {
        $fieldString = str_replace('<![CDATA[', '', $fieldString);
        $fieldString = str_replace(']]', '', $fieldString);
        return $fieldString;
    }

    private function sortMessagesByDate(array $threads): array
    {
        foreach ($threads as &$messageList) {
            // Build an array that contains only messages dates
            // Important : keys must be the same between messageList and messageTimestampList
            $messageTimestampList = [];
            foreach ($messageList as $key => $message) {
                $messageTimestampList[$key] = DateTime::createFromFormat('d/m/Y-H:i', $message['Date'])->getTimestamp();
            }

            // Sort $messageList based on timestamps contained in the $messageTimestampList
            /** @see array_multisort */
            array_multisort($messageTimestampList, $messageList);
        }

        return $threads;
    }

    /**
     * @throws Exception
     */
    private function importMessageByThread($ticket, Thread $thread, mixed $messages)
    {
        $patterns = $this->getPatterns();
        foreach ($messages as $message) {

            $messageDate = DateTime::createFromFormat('d/m/Y-H:i', $message['Date']);
            $starter_date = $this->checkMessageDate($messageDate);
            if (!$starter_date)
                continue;

            $message['id'] = crc32($message['Message'] . $message['MpCustomerId'] . $message['Date']);

            if(isset($message['Object'])){ // It's a mail
                $subject = $message['Object'];
                $normalizedSubject = $this->normalize(mb_convert_encoding($subject, 'HTML-ENTITIES'));//HTML-ENTITIES to be the same of mails import

                if (!$action = $this->getAction($patterns, $normalizedSubject)) {
                    throw new Exception('item id: ' . $message['MpItemId'] . ', no Rakuten pattern found for subject "' . $normalizedSubject . '" (' . $subject . ')');
                } else if ($this->isMessagesImported($message['id'])){
                    $this->logger->info('Check if this message is imported');
                    $action = "Ignore";
                }
            } else {
                $action = "Sales";
            }

            // Skip this message if action is "Ignore". Continue process for "Sales" and "Retract" action
            if ($action === "Ignore") {
                continue;
            } else {
                $this->logger->info('Convert api message to db message');
                $this->convertApiResponseToMessage($ticket, $message, $thread);
                $this->addImportedMessageChannelNumber($message['id']);
            }
        }
    }

    public function getPatterns(): array
    {
        $patterns = array();

        // Service client
        $patterns[] = array('name' => 'Sales', 'pattern' => '#clamationencours#'); //Réclamation en cours : faisons le point ensemble
        $patterns[] = array('name' => 'Sales', 'pattern' => '#clamationsurvente#'); //Réclamation sur vente : contactez l'acheteur
        $patterns[] = array('name' => 'Sales', 'pattern' => '#cisionssurvotrevente#');
        $patterns[] = array('name' => 'Sales', 'pattern' => '#umoncolisenretour#'); //Je n'ai pas reçu mon colis en retour (552748959) - Chihab (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#demandedepr#'); // Précisions sur votre vente
        $patterns[] = array('name' => 'Sales', 'pattern' => '#modificationdeladresse#'); //Modification de l'adresse
        $patterns[] = array('name' => 'Sales', 'pattern' => '#avezvousexp#'); //Avez-vous expédié l'article ? (542934320) - Service Clients Rakuten - PriceMinister
        $patterns[] = array('name' => 'Sales', 'pattern' => '#avezvousexp#'); //Rétractation : l'acheteur souhaite annuler sa commande (554833295) - Gwenaelle (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#questionsurlacommande#');
        $patterns[] = array('name' => 'Sales', 'pattern' => '#reclamation#');
        $patterns[] = array('name' => 'Sales', 'pattern' => '#cisionssurvotreachat#'); //Précisions sur votre achat
        $patterns[] = array('name' => 'Sales', 'pattern' => '#nouveaumessagede#'); //question sur les commande
        $patterns[] = array('name' => 'Sales', 'pattern' => '#articlenonrec#'); // Article non reçu : confirmez l'expédition (595558587) - Gwenaelle (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#derniegravererelanceenvoyezuntransporteur#'); // Dernière relance : envoyez un transporteur (598404103) - Yassir (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#messageimportant#'); // Message important - Faty (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#finalisationdevotrevente#'); // Finalisation de votre vente (601042153) - Mélisande (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#colisnonreccediluparlacheteur#'); // Colis non reçu par l'acheteur : contactez immédiatement votre transporteur (604884037) - Aicha (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#votrenumeacuterodesuiviestincorrect#'); // Votre numéro de suivi est incorrect (605579444) - Gwenaelle (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#anomaliedanslacheminementducolis#'); // Anomalie dans l'acheminement du colis (607563554) - Gwenaelle (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#reacuteclamationsurvotrevente#'); // Réclamation sur votre vente (608352102) - Service Clients Rakuten
        $patterns[] = array('name' => 'Sales', 'pattern' => '#trouverlepaiementdemavente#'); // Re: Où retrouver le paiement de ma vente - Aya (Service clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#clamationenvoyeznousdesdocuments#'); // Nouvelle réclamation - Envoyez-nous des documents (607072183) - Amina (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#repostezvotremessagepour#'); // Repostez votre message pour KathleenJ (607072183) - Service Clients Rakuten
        $patterns[] = array('name' => 'Sales', 'pattern' => '#contactezvotreacheteur#'); // Contactez votre acheteur (572649980) - Anas (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#concernantvotrereacuteponseagravelareacuteclamation#'); // Concernant votre réponse à la réclamation (609809198) - Faty (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#aproposdevotredemandedephotos#'); // A propos de votre demande de photos (610105032) - Nacim (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#problegravemedelivraisoncontactezvotretransporteur#'); // Problème de livraison : contactez votre transporteur (610297239) - Gwenaelle (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#votrerabaisaeacuteteacutetransmisagravevotreacheteur#'); // Votre rabais a été transmis à votre acheteur (606500218) - Service Clients Rakuten
        $patterns[] = array('name' => 'Sales', 'pattern' => '#reacuteponseconcernantvotrerabaissurlacommande#'); // Réponse concernant votre rabais sur la commande 606500218 - Service Clients Rakuten
        $patterns[] = array('name' => 'Sales', 'pattern' => '#relancedocumentenattente#'); // Relance : document en attente (609800813) - Chihab (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#envoyezunnouvelarticle#'); // Envoyez un nouvel article (611273362) - Soraya (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#unereacuteclamationestencourssurmavente#'); // Re: Une réclamation est en cours sur ma vente - Hamid (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#applicationdelagarantie#'); // Application de la garantie : dernière relance avant annulation (572886431) - Gwenaelle (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#aproposdevotrevente#'); // A propos de votre vente (611195501) - Bilal (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#photosreacuteclamation#'); // Photos - Réclamation 606917070
        $patterns[] = array('name' => 'Sales', 'pattern' => '#jeproposelareacuteparation#'); // Re: Je propose la réparation (611630163) - Sara (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#envoyeznouslapreuvededeacutepocirctducolis#'); // Envoyez-nous la preuve de dépôt du colis (612720767) - Chihab (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#validationdevotreportemonnaie#'); // Important - Validation de votre Porte-Monnaie Rakuten
        $patterns[] = array('name' => 'Sales', 'pattern' => '#nouvelleadresserenvoyezlarticle#'); // Nouvelle adresse : renvoyez l'article (617705973) - Amina (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#envoyeznouslapreuvedelivraison#'); // Envoyez-nous la preuve de livraison (619113773) - Faty (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#validationdevosdocuments#'); // Validation de vos documents - Rakuten
        $patterns[] = array('name' => 'Sales', 'pattern' => '#reacuteponseagravevotre#'); // Réponse à votre question - Aya (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#envoyeznousdesphotos#'); // Envoyez-nous des photos (620627768) - Faty (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#aproposdelacommande#'); // A propos de la commande 635438545 - Gwenaelle (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#reacuteparationdelarticle#'); // Réparation de l'article (606384138) - Samia (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#envoyezlebonarticle#'); // (Envoyez le bon article (641096279) - Nabil (Service Clients Rakuten))
        $patterns[] = array('name' => 'Sales', 'pattern' => '#derniegravererelancepourlenvoidesdocumentsdemandeacutes#'); //Dernière relance pour l'envoi des documents demandés (640976743) - Brahim (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#envoyeznoustouslesdocumentsdemandeacutes#'); //(Envoyez-nous tous les documents demandés (640976743) - Chihab (Service Clients Rakuten))
        $patterns[] = array('name' => 'Sales', 'pattern' => '#nouvellereacuteclamationarticleabsentducolis#'); //Nouvelle réclamation : article absent du colis (643443260) - Sara (Service Clients Rakuten
        $patterns[] = array('name' => 'Sales', 'pattern' => '#reacuteclamationconfirmeacuteearticlereacuteexpeacutedieacute#'); //Réclamation confirmée : article réexpédié (642786340) - Nabil (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#ougraveenestmonremboursement#'); //(Re: Où en est mon remboursement - Magali (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#vousavezannuleacutevotrereacuteclamation#'); //(Vous avez annulé votre réclamation (645245251) - Patricia (Service Clients Rakuten))
        $patterns[] = array('name' => 'Sales', 'pattern' => '#reacutegularisationdevotrepaiement#'); //(Régularisation de votre paiement (636980945) - Chihab (Service Clients Rakuten))
        $patterns[] = array('name' => 'Sales', 'pattern' => '#garantieconfirmeznouslapriseencharge#'); //(Garantie : confirmez-nous la prise en charge (642194373) - Lalie (Service Clients Rakuten))
        $patterns[] = array('name' => 'Sales', 'pattern' => '#lacheteurnapasconfirmeacutelareacuteception#'); //(Re: L'acheteur n'a pas confirmé la réception - Magali (Service Clients Rakuten))
        $patterns[] = array('name' => 'Sales', 'pattern' => '#concernantlexpeacutedition#'); //(Concernant l'expédition (646031961) - Aimé (Service Clients Rakuten))
        $patterns[] = array('name' => 'Sales', 'pattern' => '#retourdevotrearticletraitementencours#'); //(Retour de votre article : traitement en cours (646547784) - Karole (Service Clients Rakuten))
        $patterns[] = array('name' => 'Sales', 'pattern' => '#problegravemedelivraisonenvoyeznousledocument#'); //(Problème de livraison : envoyez-nous le document (645213712) - Valerie (Service Clients Rakuten))
        $patterns[] = array('name' => 'Sales', 'pattern' => '#confirmezleretourdelarticle#'); //(Confirmez le retour de l'article chez le vendeur - Santi (Service Clients Rakuten))
        $patterns[] = array('name' => 'Sales', 'pattern' => '#envoyezlecompleacutementdelarticle#'); //(Envoyez le complément de l'article (647438071) - Hamid (Service Clients Rakuten))
        $patterns[] = array('name' => 'Sales', 'pattern' => '#ladresseestcorrecteenvoyezlarticle#'); //(Re: L'adresse est correcte, envoyez l'article - Naya (Service Clients Rakuten))
        $patterns[] = array('name' => 'Sales', 'pattern' => '#commentcontactervotreacheteur#'); // (Comment contacter votre acheteur (648321258) - Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#ausujetdelareacuteclamationdevotreacheteur#'); // (Au sujet de la réclamation de votre acheteur - Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#annulationdevotrevente#'); // Annulation de votre vente (622898193) - DanielAN (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#demandedegarantiepreacutecisezleproblegraveme#'); // (Demande de garantie : précisez le problème (642760454) - LauraR (Service Clients Rakuten))
        $patterns[] = array('name' => 'Sales', 'pattern' => '#deacutelaidereacuteclamationdeacutepasseacute#'); // (Délai de réclamation dépassé (565401053) - Aya (Service Clients Rakuten))
        $patterns[] = array('name' => 'Sales', 'pattern' => '#jesouhaitecreacuteditermonportemonnaie#'); // (Re : Je souhaite créditer mon Porte-Monnaie - Lalie (Service Clients Rakuten))
        $patterns[] = array('name' => 'Sales', 'pattern' => '#reacuteclamationinformationssuppleacutementairesrequises#'); // (Re : Je souhaite créditer mon Porte-Monnaie - Lalie (Service Clients Rakuten))
        $patterns[] = array('name' => 'Sales', 'pattern' => '#garantiedonneznousplusdinformation#'); // (Garantie : donnez-nous plus d'informations (650319289) - Axel (Service Clients Rakuten))
        $patterns[] = array('name' => 'Sales', 'pattern' => '#confirmeznouslexpeacuteditiondelacommande#'); // (Confirmez-nous l'expédition de la commande (650264580) - Magali (Service Clients Rakuten))
        $patterns[] = array('name' => 'Sales', 'pattern' => '#suiteagravelareacuteexpeacuteditiondelarticle#'); //(Suite à la réexpédition de l'article (651276796) - Fabiola (Service Clients Rakuten))
        $patterns[] = array('name' => 'Sales', 'pattern' => '#confirmationdevotreenvoi#'); //(Confirmation de votre envoi (651355163) - Rachida (Service Clients Rakuten))
        $patterns[] = array('name' => 'Sales', 'pattern' => '#confirmezlenregistrementdevotrecolis#'); //(Confirmez l'enregistrement de votre colis (622898193) - LisaR (Service Clients Rakuten))
        $patterns[] = array('name' => 'Sales', 'pattern' => '#acheminementdevotrecolis#'); //(Acheminement de votre colis (653528798) - Patricia (Service Clients Rakuten))
        $patterns[] = array('name' => 'Sales', 'pattern' => '#reacuteponsenonrecevableenvoyeznouslebondocument#'); //(Réponse non recevable : envoyez-nous le bon document (654295060) - Patricia (Service Clients Rakuten))
        $patterns[] = array('name' => 'Sales', 'pattern' => '#nousnavonspasreccediluvotremessage#'); //(Nous n'avons pas reçu votre message - Lucky (Service Clients Rakuten))
        $patterns[] = array('name' => 'Sales', 'pattern' => '#requandseraijepayeacutepourmavente#'); //Re: Quand serai-je payé pour ma vente - FredR (Service clients Rakuten))
        $patterns[] = array('name' => 'Sales', 'pattern' => '#problegravemedelivraisonnouvelleexpeacutedition#'); // Problème de livraison : nouvelle expédition (656416667) - Ange (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#annulationenvoyezuntransporteur#'); // Annulation : envoyez un transporteur (654957696) - Axel (Service Clients Rakuten)
        $patterns[] = array('name' => 'Sales', 'pattern' => '#renvoyeznouslesdocuments#'); // (Renvoyez-nous les documents (652953733) - Aimé (Service Clients Rakuten))
        $patterns[] = array('name' => 'Sales', 'pattern' => '#confirmationderetournumeacuterodesuivierroneacute#'); // (Confirmation de retour : numéro de suivi erroné - Eulalie (Service Clients Rakuten))
        $patterns[] = array('name' => 'Sales', 'pattern' => '#problegravemedelivraisonrenvoyezlarticle#'); //(Problème de livraison : renvoyez l'article (659272302) - Chris (Service Clients Rakuten))
        $patterns[] = array('name' => 'Sales', 'pattern' => '#nousavonsbienreccediluvotremessage#'); //Nous avons bien reçu votre message - Service Clients Rakuten
        $patterns[] = array('name' => 'Sales', 'pattern' => '#reacuteclamationtraitementencours#'); // Réclamation : traitement en cours (598170056) - Brahim (Service Clients Rakuten)

        $patterns[] = array('name' => 'Retract', 'pattern' => '#acheteursouhaiteannulersacommande#');
        $patterns[] = array('name' => 'Retract', 'pattern' => '#tractation#');
        $patterns[] = array('name' => 'Retract', 'pattern' => '#demandedapplicationdelagarantie#');

        // Ignore
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#vousavezunenouvellevente#'); //Félicitations, vous avez une nouvelle vente !
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#finalisationdevotrepaiement#');
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#ditiondevotrearticle#');
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#votrepropositionderabais#');
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#noteetcommentairevalides#');
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#modificationdevotrenote#');
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#reversementenvoy#');
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#reversementparvirement#');
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#questionsurunproduitquevousvendez#'); //Question sur un produit que vous vendez (forum)
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#prcisionssurvotrevente#');//question sur les produits bia le forum // plus tard
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#reacuteclamationclose#');
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#avezvousbienreccedilulinteacutegraliteacutedevotrecommande#');
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#reacuteclamationrelanceacutee#'); // Réclamation relancée (598170056) - Gwenaelle (Service Clients Rakuten) : CONCERNE LES COMMANDES B2C
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#enregistrementdevotrereacuteclamation#'); // Enregistrement de votre réclamation (598170056) - Service Clients Rakuten) : CONCERNE LES COMMANDES B2C
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#suiteagravevotreappelenquecirctedesatisfaction#'); // Suite à votre appel : enquête de satisfaction (Service Clients Rakuten)
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#reacuteponseduvendeuragravevotrereacuteclamation#'); // Réponse du vendeur à votre réclamation (598170056) - Soraya (Service Clients Rakuten) : CONCERNE LES COMMANDES B2C
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#enregistrementdevotrecommande#'); // Enregistrement de votre commande (achat n°327639138) : CONCERNE LES COMMANDES B2C
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#bilandevotrecommande#'); // Bilan de votre commande (achat n°327639138) : CONCERNE LES COMMANDES B2C
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#votrefacturerakutenfrance#'); // Votre facture RAKUTEN France du 10/11/2020 : CONCERNE LES COMMANDES B2C
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#levendeurvousenvoielecompleacutement#'); // Le vendeur vous envoie le complément (598170056) - Yassir (Service Clients Rakuten) : CONCERNE LES COMMANDES B2C
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#reacuteclamationtransmiseauvendeur#'); // Réclamation transmise au vendeur (603331272) - (Service Clients Rakuten) : CONCERNE LES COMMANDES B2C
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#commentcontacterlacheteur#'); // Re: Comment contacter l'acheteur - Amar (Service Clients Rakuten)
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#votrecommandenatoujourspaseacuteteacuteexpeacutedieacutee#'); // Votre commande n'a toujours pas été expédiée (605257560)
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#noubliezpasdexpeacutediervotrecommande#'); // N'oubliez pas d'expédier votre commande ! (605967007)
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#votrecommandeestenretard#'); // Votre commande est en retard (604755476)
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#resuiteagravemonmessage#'); // Re: Suite à mon message - Faty (Service Clients Rakuten)
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#aproposdevotrecommande#'); // A propos de votre commande (607243742) - Gwenaelle (Service Clients Rakuten)
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#envoyezauplusvitelacommande#'); // Envoyez au plus vite la commande (605121096) - Aya (Service Clients Rakuten)
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#respectdesdeacutelaisdexpeacutedition#'); // Respect des délais d'expédition - Faty (Service Clients Rakuten)
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#reacuteclamationnouscontactonsvotrevendeur#'); // Réclamation : nous contactons votre vendeur (607243742) - Mahdi (Service Clients Rakuten)
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#annulationdevotreachat#'); // Annulation de votre achat (607243742) - Matis (Service Clients Rakuten)
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#remboursementdevotreachat#'); // Remboursement de votre achat (607243742) - Matis (Service Clients Rakuten)
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#planningopeacuterationscommerciales#'); // Planning opérations commerciales Février 21 Rakuten - Sales operations planning February 21 Rakuten
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#suividelexpeacuteditiondevotrecommande#'); // Suivi de l'expédition de votre commande (366679233) : CONCERNE LES COMMANDES B2C
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#confirmezlareacuteceptiondelarticle#'); // Confirmez la réception de l'article (615568327) - Service Clients Rakuten : CONCERNE LES COMMANDES B2C
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#vousavezdesnouveauxmessages#'); // Vous avez des nouveaux messages
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#reacuteceptiondevosdocuments#'); // Réception de vos documents - Rakuten
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#jeparsenvacances#'); // Je pars en vacances
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#jerentredevacances#'); // Je rentre de vacances
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#votreavisestessentiel#'); // Votre avis est essentiel !
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#votrecommandeestencoursdepreacuteparation#'); //(Votre commande est en cours de préparation ! (642760454) - Gwenaelle
        $patterns[] = array('name' => 'Ignore', 'pattern' => '#remoncomptepro#'); //(Re: Mon compte PRO - Penda (Service commercial Rakuten))

        return $patterns;
    }

    public function normalize($subject): string
    {
        $newSubject = '';
        $subject = strtolower($subject);

        for ($i = 0; $i < strlen($subject); $i++) {
            if ((ord($subject[$i]) >= 97) and (ord($subject[$i]) <= 122)) {
                $newSubject .= $subject[$i];
            }
        }

        return $newSubject;
    }

    protected function getAction($patterns, $normalizedSubject)
    {
        foreach ($patterns as $pattern) {
            if (preg_match($pattern['pattern'], $normalizedSubject)) {
                return $pattern['name'];
            }
        }
        return false;
    }
}
