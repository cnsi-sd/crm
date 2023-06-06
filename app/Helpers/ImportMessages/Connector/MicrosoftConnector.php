<?php

namespace App\Helpers\ImportMessages\Connector;

use _PHPStan_67a5964bf\Nette\Utils\DateTime;
use App\Helpers\EmailAttachementNormalized;
use App\Helpers\EmailNormalized;
use App\Helpers\TmpFile;
use Cnsi\Logger\Logger;
use Exception;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class MicrosoftConnector
{

    protected array $credentials;

    protected Logger $logger;

    private string $accessToken;

    /**
     * @param array $credentials
     * @param Logger $logger
     * @throws IdentityProviderException
     */
    public function __construct(array $credentials, Logger $logger)
    {
        $this->credentials = $credentials;
        $this->logger = $logger;
        $this->microsoftConnection();
    }

    public static function getConnectionLink(): string
    {
        $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId' => config('azure.appId'),
            'clientSecret' => config('azure.appSecret'),
            'redirectUri' => config('azure.redirectUri'),
            'urlAuthorize' => config('azure.authority') . config('azure.authorizeEndpoint'),
            'urlAccessToken' => config('azure.authority') . config('azure.tokenEndpoint'),
            'urlResourceOwnerDetails' => '',
            'scopes' => config('azure.scopes')
        ]);

        $authUrl = $oauthClient->getAuthorizationUrl();
        setting(['tmp.oauthState' => $oauthClient->getState()]);
        setting()->save();

        return $authUrl;
    }

    /**
     * @throws IdentityProviderException
     */
    protected function microsoftConnection(): void
    {
        $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId' => $this->credentials['username'],
            'clientSecret' => $this->credentials['password'],
            'redirectUri' => $this->credentials['host'],
            'urlAuthorize' => config('azure.authority') . config('azure.authorizeEndpoint'),
            'urlAccessToken' => config('azure.authority') . config('azure.tokenEndpoint'),
            'urlResourceOwnerDetails' => '',
            'scopes' => config('azure.scopes')
        ]);

        if (setting('MicrosoftGAccessToken') !== null || setting('MicrosoftTokenExpiredTime') !== null && setting('MicrosoftTokenExpiredTime') < time()) {
            $accessToken = $oauthClient->getAccessToken('refresh_token', [
                'refresh_token' => setting('MicrosoftRefreshToken')
            ]);

            setting(['MicrosoftGAccessToken' => $accessToken->getToken()]);
            setting(['MicrosoftRefreshToken' => $accessToken->getRefreshToken()]);
            setting(['MicrosoftTokenExpiredTime' => $accessToken->getExpires()]);
            setting()->save();
        }
        $this->accessToken = setting('MicrosoftGAccessToken');
    }

    /**
     * @throws \Exception
     */
    public function getEmails($from_date)
    {
        $this->logger->info('https://graph.microsoft.com/v1.0/me/messages?$search="received:' . $from_date . '"');
        $messageUrlGraph = 'https://graph.microsoft.com/v1.0/me/messages?$search="received:' . $from_date . '"';

        $listEmails = [];
        $listAttachment = [];
        $curl = curl_init($messageUrlGraph);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $this->accessToken
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        if ($response === false) {
            throw new Exception('Erreur : ' . curl_error($curl));
        }

        $email_data = json_decode($response, true);
        if (!array_key_exists('error', $email_data)) {
            foreach ($email_data['value'] as $email) {
                if ($email['hasAttachments']) {
                    $url = "https://graph.microsoft.com/v1.0/me/messages/{$email['id']}/attachments";
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        "Authorization: Bearer {$this->accessToken}",
                        "Content-Type: application/json"
                    ));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($ch);
                    curl_close($ch);

                    $attachments = json_decode($response)->value;
                    foreach ($attachments as $attachment) {
                        $tmpFile = new TmpFile((string)$attachment->contentBytes);
                        $listAttachment[] = new EmailAttachementNormalized($attachment->name, $tmpFile);
                    }
                }
            }

            $listEmails[$email['id']] = (new EmailNormalized())
                ->setEmailId($email['id'])
                ->setDate(new DateTime($email['receivedDateTime']))
                ->setSender($email['sender']['emailAddress']['address'])
                ->setHeader($email['body']['content'])
                ->setFromAddress($email['from']['emailAddress']['address'])
                ->setSubject($email['subject'])
                ->setHasAttachments($email['hasAttachments'])
                ->setAttachments($listAttachment)
                ->setContent($email['body']['content']);
        }
        return $listEmails;
    }
}
