<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-28, 01:41.
 * @license Apache-2.0
 */
 include 'header.php';

try {
    $order = $client->requestOrder(
        'GE',
        5,
        \RagingProdigy\Alpaca\Constants\OrderAction::BUY,
        \RagingProdigy\Alpaca\Constants\OrderType::MARKET,
        \RagingProdigy\Alpaca\Constants\TimeInForce::DAY
    );

    echo $order;

    $sellOrder = $client->requestOrder(
        'GE',
        3,
        \RagingProdigy\Alpaca\Constants\OrderAction::SELL,
        \RagingProdigy\Alpaca\Constants\OrderType::MARKET,
        \RagingProdigy\Alpaca\Constants\TimeInForce::DAY
    );

    echo $sellOrder;
} catch (Exception $e) {
    print_r($e->getMessage());
}
