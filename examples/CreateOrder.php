<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-28, 01:41.
 * @license Apache-2.0
 */
include 'header.php';

try {
    $order = $client->requestNewOrder(
        'GE',
        50,
        \RagingProdigy\Alpaca\Constants\OrderAction::BUY,
        \RagingProdigy\Alpaca\Constants\OrderType::MARKET,
        \RagingProdigy\Alpaca\Constants\TimeInForce::DAY
    );

    echo $order;

//    $sellOrder = $client->requestOrder(
//        'GE',
//        4,
//        \RagingProdigy\Alpaca\Constants\OrderAction::SELL,
//        \RagingProdigy\Alpaca\Constants\OrderType::MARKET,
//        \RagingProdigy\Alpaca\Constants\TimeInForce::DAY
//    );
//
//    echo $sellOrder;
} catch (\RagingProdigy\Alpaca\Exceptions\InvalidApiUsageException $e) {
    print_r($e->getMessage());
}

foreach ($client->getOpenPositions() as $calendar) {
    echo $calendar . "\n";
}
