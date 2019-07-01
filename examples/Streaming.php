<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-07-01, 01:16.
 * @license Apache-2.0
 */

use RagingProdigy\Alpaca\Constants\DataStream;
use RagingProdigy\Alpaca\Entities\AccountUpdateEvent;
use RagingProdigy\Alpaca\Entities\OrderUpdateEvent;
use RagingProdigy\Alpaca\Entities\UpdateEvent;
use Ratchet\Client\WebSocket;

include 'header.php';

$alpacaClient->connectToStreams([DataStream::ACCOUNT_UPDATES, DataStream::TRADE_UPDATES], static function (WebSocket $webSocket, UpdateEvent $event = null) {
    if ($event instanceof OrderUpdateEvent) {
        echo "Order Update Event: $event\n";
    }

    if ($event instanceof AccountUpdateEvent) {
        echo "Account Update Event: $event\n";
    }
});
