<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-28, 01:41.
 * @license Apache-2.0
 */

use RagingProdigy\Alpaca\Constants\OrderAction;
use RagingProdigy\Alpaca\Constants\OrderType;
use RagingProdigy\Alpaca\Constants\TimeInForce;

include 'header.php';

$order = $alpacaClient->requestNewOrder(
    'GE',
    50,
    OrderAction::BUY,
    OrderType::MARKET,
    TimeInForce::DAY
);
echo $order;

$sellOrder = $alpacaClient->requestNewOrder(
    'GE',
    4,
    OrderAction::SELL,
    OrderType::MARKET,
    TimeInForce::DAY
);
echo $sellOrder;

// Get Open Positions
foreach ($alpacaClient->getOpenPositions() as $position) {
    echo $position . "\n";
}
