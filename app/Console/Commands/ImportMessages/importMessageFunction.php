<?php

namespace App\Console\Commands\ImportMessages;

trait importMessageFunction {

    /**
     * Return true if a message is already imported for this marketplace
     * @param $marketplace_message_id
     * @return bool
     */
    protected function isMessageAlreadyImportedById($marketplace_message_id)
    {
        $this->loadAlreadyImportedIds();
        return isset(self::$alreadyImportedIds[$marketplace_message_id]);
    }

}
