<?php

namespace App\Jobs\Bot\Answers;

use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Helpers\Prestashop\CrmLinkGateway;
use App\Models\Channel\DefaultAnswer;
use App\Models\Channel\SavNote;
use App\Models\Ticket\Message;
use App\Models\Ticket\Thread;

class SendSav extends AbstractAnswer
{

    /**
     * @inheritDoc
     */
    public function handle(): bool
    {
        if (!$this->canBeProcessed())
            return self::SKIP;

        $prestashopOrder = $this->message->thread->ticket->order->getFirstPrestashopOrder();
        $products = $prestashopOrder['products'];
        foreach ($products as $product){
            $productGroup = $product['product_group'];
            switch ($productGroup){
                case 'P 30':
                    self::SavDelivery($this->message->thread, setting('bot.sav.gem_answer_id'),$prestashopOrder);
                    break;
                default:
                    self::SavDelivery($this->message->thread, setting('bot.sav.pem_answer_id'),$prestashopOrder);
            }
        }
    }

    protected function canBeProcessed(): bool
    {
        if (!setting('bot.sav.active'))
            return false;

        if (!$this->message->isExternal())
            return false;

        if (!$this->message->isFirstMessageOnThread())
            return false;

        if ($this->message->hasBeenAnswered())
            return false;

        if (!$this->matchPatterns($this->message))
            return false;

        return true;
    }

    private function matchPatterns(Message $message): bool
    {
        $patterns = [
            '(panne)',
            '(sav)',
            '(ne fonctionne pas)',
            '(bruit)',
            '(froid)',
            '(s\'éteint)',
        ];

        $threadSubject = $message->thread->name;
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $threadSubject, $matches)) {
                return true;
            }
        }

        return false;
    }

    protected function SavDelivery(Thread $thread, int $messageId, $prestashopOrder): void
    {
        $defaultAnswer = DefaultAnswer::findOrFail($messageId);
        $messageQuerycount = Message::query()->where("thread_id", $thread->id)->where("content", $defaultAnswer->content)->count();
        $message = str_replace('[__brand_list__]', $this->brandList($prestashopOrder), $defaultAnswer->content);
        if ($messageQuerycount == 0) {
            Message::firstOrCreate([
                'thread_id' => $thread->id,
                'content' => strip_tags($message)
            ],
                [
                    'channel_message_number' => null,
                    'user_id' => null,
                    'author_type' => TicketMessageAuthorTypeEnum::SYSTEM,
                ]
            );
        }
    }

    protected function brandList($prestashopOrder): string
    {
        $products = $prestashopOrder['products'];
        $contactBrand = ' </br>';
        foreach ($products as $product){
            $brand = strtoupper($product['brand']);
            foreach (self::SavNum as $key => $value){
                if($key === $brand){
                    $contactBrand .= ' - ' . $key . ' au ' . $value . ' </br>';
                }
            }
        }
        return $contactBrand;
    }

    const SavNum = [
        "SAMSUNG"               => "01 48 63 00 00",
        "BOSCH"                 => "01 40 10 11 00",
        "SIEMENS"               => "01 40 10 12 00",
        "NEFF"	                => "01 40 10 42 10",
        "DAEWOO"	            => "09 69 32 82 52",
        "SHARP"	                => "08 09 10 15 15",
        "HAIER"	                => "09 80 40 64 09",
        "SCHNEIDER"	            => "01 61 44 02 70",
        "SOGELUX"	            => "02 32 19 06 39",
        "GLEM AIRLUX"	        => "03 24 56 67 04",
        "ELICA"                 => "04 88 78 59 48",
        "AMICA"	                => "01 56 48 06 31",
        "ROBLIN"	            => "01 30 28 94 00",
        "LG"	                => "08 00 99 55 55",
        "TELEFUNKEN"	        => "02 32 19 06 39",
        "DE DIETRICH"	        => "09 69 39 25 25",
        "MANHATTAN"             => "",
        "PHILIPS"	            => "01 57 32 40 50",
        "TCL / THOMSON"	        => "01 87 16 13 99",
        "Toshiba"	            => "08 09 10 01 11",
        "MOULINEX"	            => "09 74 50 10 14",
        "KITCHEN CHEF"	        => "04 42 63 15 33",
        "BOSCH PEM"	            => "01 40 10 42 15",
        "STEAMONE"	            => "01 47 63 49 39",
        "THOMSON"               => "01 60 60 49 59",
        /*"THOMSON ordinateur"    => "01 60 60 49 59",*/
        "GRUNDIG"               => "",
        "ILYAMA"	            => "09 79 99 03 99",
        "TECHWOOD"	            => "04 88 08 95 25",
        "BRAUN - ORAL B"        =>	"800 944 803",
        "BROTHER"	            => "01 49 90 61 09",
        "DELONGHI"	            => "" ,
        "ROWENTA"               =>	"09 74 50 36 23",
        "HP"	                => "08 25 00 41 23",
        "TAURUS"	            => "03 86 83 64 00",
        "XIAOMI"	            => "805 370 916",
        "HISENSE"	            => "01.76.49.05.05",

    ];
}
