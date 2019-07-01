<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-07-01, 15:24.
 * @license Apache-2.0
 */

use RagingProdigy\Alpaca\Constants\BarTimeFrame;
use RagingProdigy\Alpaca\Constants\DataStream;
use RagingProdigy\Alpaca\Constants\OrderAction;
use RagingProdigy\Alpaca\Constants\OrderType;
use RagingProdigy\Alpaca\Constants\TimeInForce;
use RagingProdigy\Alpaca\Entities\AccountUpdateEvent;
use RagingProdigy\Alpaca\Entities\OrderUpdateEvent;
use RagingProdigy\Alpaca\Entities\UpdateEvent;
use Ratchet\Client\WebSocket;

// Listen for Updates
$alpacaClient->connectToStreams([DataStream::ACCOUNT_UPDATES, DataStream::TRADE_UPDATES], static function (WebSocket $webSocket, UpdateEvent $event = null) {
    if ($event instanceof OrderUpdateEvent) {
        echo "Order Update Event Received: $event\n";
    }

    if ($event instanceof AccountUpdateEvent) {
        echo "Account Update Event Received: $event\n";
    }
});

// Get Account Info
$account = $alpacaClient->getAccount();

echo "\nAccount Information: \n";
echo "\tCreated At: {$account->getCreatedAt()}\n";
echo "\tBuying Power: $ {$account->getBuyingPower()}\n";
echo "\tPortfolio Value: $ {$account->getPortfolioValue()}\n\n";

// Get Market Clock
$clock = $alpacaClient->getClock();

echo "Clock: \n";
echo "\tCurrent Time: {$clock->getTimestamp()}\n";
echo "\tMarket Next Open Time: {$clock->getNextOpen()}\n";
echo "\tMarket Next Close Time: {$clock->getNextClose()}\n\n";

// Request Purchase Order
$order = $alpacaClient->requestNewOrder(
    'AAPL',
    5,
    OrderAction::BUY,
    OrderType::MARKET,
    TimeInForce::DAY
);

echo "Market Order Response: \n";
echo "\tSymbol: {$order->getSymbol()}\n";
echo "\tClient Order Id: {$order->getClientOrderId()}\n";
echo "\tQuantity: {$order->getQuantity()}\n";
echo "\tType: {$order->getType()}\n";
echo "\tCreated At: {$order->getCreatedAt()}\n\n";

// Find Order By ID
$marketOrder = $alpacaClient->getOrder($order->getId());

echo "Market Order by ID Response: \n";
echo "\tSymbol: {$marketOrder->getSymbol()}\n";
echo "\tClient Order Id: {$marketOrder->getClientOrderId()}\n";
echo "\tQuantity: {$marketOrder->getQuantity()}\n";
echo "\tType: {$marketOrder->getType()}\n";
echo "\tCreated At: {$marketOrder->getCreatedAt()}\n\n";

// Find Order By Client Order ID
$orderByClientOrderId = $alpacaClient->getOrderByClientOrderId($order->getClientOrderId());

echo "Market Order by ID Response: \n";
echo "\tSymbol: {$orderByClientOrderId->getSymbol()}\n";
echo "\tClient Order Id: {$orderByClientOrderId->getClientOrderId()}\n";
echo "\tQuantity: {$orderByClientOrderId->getQuantity()}\n";
echo "\tType: {$orderByClientOrderId->getType()}\n";
echo "\tCreated At: {$orderByClientOrderId->getCreatedAt()}\n\n";

// Cancel Order
$alpacaClient->cancelOrder($order->getId()); // Would raise an exception if not successful

// Get Bars
$bars = $alpacaClient->getBars(['AAPL'], BarTimeFrame::DAY, 4);

echo "Bars Response: \n";
foreach ($bars as $bar) {
    echo "\t========================\n";
    echo "\tSymbol: {$bar->getSymbol()}\n";
    echo "\tOpen: {$bar->getO()}\n";
    echo "\tHigh: {$bar->getH()}\n";
    echo "\tLow: {$bar->getL()}\n";
    echo "\tClose: {$bar->getC()}\n";
    echo "\tVolume: {$bar->getV()}\n";
}
