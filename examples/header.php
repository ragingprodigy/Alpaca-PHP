<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-28, 01:41.
 * @license Apache-2.0
 */

require __DIR__ . '/../vendor/autoload.php';

use RagingProdigy\Alpaca\Client;
use RagingProdigy\Alpaca\Config;
use RagingProdigy\Alpaca\Constants\BarTimeFrame;
use RagingProdigy\Alpaca\Constants\OrderAction;
use RagingProdigy\Alpaca\Constants\OrderType;
use RagingProdigy\Alpaca\Constants\TimeInForce;

$alpacaClient = new Client(new Config('PKWVTQ6CRN9286WQTAKY', 'j4Z1SmY0OzMgqnMxW2VQTWn4LA/REsqIzKfD5TJv'));

$account = $alpacaClient->getAccount();

// Get Account Info
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

// TODO: ORDER Update Stream

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

// TODO: Order Update STREAM

// Get Bars
$bars = $alpacaClient->getBars(['AMZN'], BarTimeFrame::DAY, 4);

echo "Bars Response: \n";
foreach ($bars as $bar) {
    echo "\t========================\n";
    echo "\tSymbol: {$bar->getSymbol()}\n";
    echo sprintf("\tTime: %s\n", (new DateTime($bar->getT()))->format(DATE_ATOM));
    echo "\tOpen: {$bar->getO()}\n";
    echo "\tHigh: {$bar->getH()}\n";
    echo "\tLow: {$bar->getL()}\n";
    echo "\tClose: {$bar->getC()}\n";
    echo "\tVolume: {$bar->getV()}\n";
}

