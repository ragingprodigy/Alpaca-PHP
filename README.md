# Alpaca-PHP
Alpaca Trade API for PHP [![Build Status](https://travis-ci.org/ragingprodigy/Alpaca-PHP.svg?branch=master)](https://travis-ci.org/ragingprodigy/Alpaca-PHP) [![Build Status](https://img.shields.io/badge/%20-composer-yellowgreen.svg)](https://packagist.org/packages/ragingprodigy/alpaca-php)

## Overview
This is a PHP Client for <a href="https://alpaca.markets/">Alpaca</a> (<a href="https://docs.alpaca.markets/api-documentation/web-api/">General Alpaca API Documentation</a>).  Alpaca API lets you build and trade with real-time market data for free.

### Table of Contents
1. [Installation](#installation)
2. [Basic Usage](#basic-usage)
3. [Full Scale Example](#full-scale-example)
3. [Methods](#methods)

#### Installation

The package can be installed via Composer.

    composer require ragingprodigy/alpaca-php

#### Basic Usage

To use the Library, simply create an instance of the client as shown below:
```php
use RagingProdigy\Alpaca\Client;
use RagingProdigy\Alpaca\Config;

// Paper Trading Client
$client = new Client(new Config('apiKey', 'secretKey'));

// Live Trading Client
$client = new Client(new Config('apiKey', 'secretKey', false));

// Overriding Trading and Data Api URLs
$client = new Client(new Config('apiKey', 'secretKey', true, 'https://trading.url/v2/', 'https://data.url/v1/'));
```
    
Under the Hood, the library makes use of the Guzzle Http client library to send HTTP requests. You can also provide a custom ClientInterface as the second argument to the `Client` constructor in order to further customize the GuzzleHttp Client.
    
You can then access the available APIs by calling:
```php    
$account = $client->getAccount(); // Returns an instance of "Ragingprodigy\Alpaca\Entities\Account"
$orders = $client->getOrders(); // Returns an array of "Ragingprodigy\Alpaca\Entities\Order" instances
```

#### Full Scale Example

Here's how you can use the library to print out account information, submit a limit order, and print out bars.

```php
use RagingProdigy\Alpaca\Client;
use RagingProdigy\Alpaca\Config;
use RagingProdigy\Alpaca\Constants\BarTimeFrame;
use RagingProdigy\Alpaca\Constants\DataStream;
use RagingProdigy\Alpaca\Constants\OrderAction;
use RagingProdigy\Alpaca\Constants\OrderType;
use RagingProdigy\Alpaca\Constants\TimeInForce;
use RagingProdigy\Alpaca\Entities\AccountUpdateEvent;
use RagingProdigy\Alpaca\Entities\OrderUpdateEvent;
use RagingProdigy\Alpaca\Entities\UpdateEvent;
use Ratchet\Client\WebSocket;

$alpacaClient = new Client(new Config('PKWVTQ6CRN9286WQTAKY', 'j4Z1SmY0OzMgqnMxW2VQTWn4LA/REsqIzKfD5TJv'));

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
$bars = $alpacaClient->getBars(['AMZN'], BarTimeFrame::DAY, 4);

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
```

The output for the code above would be similar to:

    Account Information: 
            Created At: 2019-06-26T15:19:01.819453Z
            Buying Power: $ 398861.06
            Portfolio Value: $ 100003.99
    
    Clock: 
            Current Time: 2019-07-01T03:14:37.582893836-04:00
            Market Next Open Time: 2019-07-01T09:30:00-04:00
            Market Next Close Time: 2019-07-01T16:00:00-04:00
    
    Market Order Response: 
            Symbol: AAPL
            Client Order Id: 309626b7-5569-4ae5-bb16-d93930b8b82b
            Quantity: 5
            Type: market
            Created At: 2019-07-01T07:14:39.022538874Z
            
    Order Update Event: {"event":"new","order":{"asset_class":"us_equity","asset_id":"b0b6dd9d-8b9b-48a9-ba46-b9d54906e415","canceled_at":null,"client_order_id":"abea2d0b-2340-4034-a50c-b4f09cde1e0b","created_at":"2019-07-01T07:12:16.177026Z","expired_at":null,"failed_at":null,"filled_at":null,"filled_avg_price":null,"filled_qty":"0","id":"b3332b17-b7dc-4a55-9a3c-dfd0c50ecc62","limit_price":null,"order_type":"market","qty":"5","side":"buy","status":"new","stop_price":null,"submitted_at":"2019-07-01T07:12:16.165806Z","symbol":"AAPL","time_in_force":"day","type":"market","updated_at":"2019-07-01T07:12:20.373783079Z"}}
    
    Market Order by ID Response: 
            Symbol: AAPL
            Client Order Id: 309626b7-5569-4ae5-bb16-d93930b8b82b
            Quantity: 5
            Type: market
            Created At: 2019-07-01T07:14:39.022539Z
    
    Market Order by ID Response: 
            Symbol: AAPL
            Client Order Id: 309626b7-5569-4ae5-bb16-d93930b8b82b
            Quantity: 5
            Type: market
            Created At: 2019-07-01T07:14:39.022539Z
            
    Order Update Event: {"event":"canceled","order":{"asset_class":"us_equity","asset_id":"b0b6dd9d-8b9b-48a9-ba46-b9d54906e415","canceled_at":"2019-07-01T07:12:20.369944583Z","client_order_id":"abea2d0b-2340-4034-a50c-b4f09cde1e0b","created_at":"2019-07-01T07:12:16.177026Z","expired_at":null,"failed_at":null,"filled_at":null,"filled_avg_price":null,"filled_qty":"0","id":"b3332b17-b7dc-4a55-9a3c-dfd0c50ecc62","limit_price":null,"order_type":"market","qty":"5","side":"buy","status":"canceled","stop_price":null,"submitted_at":"2019-07-01T07:12:16.165806Z","symbol":"AAPL","time_in_force":"day","type":"market","updated_at":"2019-07-01T07:12:20.384272245Z"},"timestamp":"2019-07-01T07:12:20.369944583Z"}
    
    Bars Response: 
            ========================
            Symbol: AAPL
            Open: 198.43
            High: 199.26
            Low: 195.29
            Close: 195.57
            Volume: 18361798
            ========================
            Symbol: AAPL
            Open: 197.83
            High: 200.99
            Low: 197.35
            Close: 199.8
            Volume: 22499633
            ========================
            Symbol: AAPL
            Open: 200.29
            High: 201.57
            Low: 199.57
            Close: 199.73
            Volume: 15460769
            ========================
            Symbol: AAPL
            Open: 199.24
            High: 199.4
            Low: 197.05
            Close: 197.92
            Volume: 18773228

#### Methods
All API methods are available on the Alpaca Client (`RagingProdigy\Alpaca\Client`).

##### Account API
Calls `GET /account` and returns the current account

```php
$client->getAccount(); //Account
```

##### Orders API
###### Request New Order
Calls `POST /orders` and creates a new order.

```php
$client->requestNewOrder(
    string $symbol,
    int $quantity,
    string $action, // OrderAction::BUY, OrderAction::SELL
    string $type, // OrderType::MARKET, OrderType::STOP, OrderType::LIMIT, OrderType::STOP_LIMIT, 
    string $timeInForce, // TimeInForce::DAY, TimeInForce::GTC, TimeInForce::OPG, TimeInForce::IOC, TimeInForce::FOK
    float $limitPrice = null,
    float $stopPrice = null,
    bool $extendedHours = false,
    $clientOrderId = null
); // Order
```

###### Get Orders
Calls `GET /orders` and returns an array of Order objects.

```php
$client->getOrders(
    string $status = OrderStatus::OPEN, // OrderStatus::CLOSED, OrderStatus::OPEN, OrderStatus::ALL
    int $limit = 50,
    DateTime $after = null,
    DateTime $until = null,
    string $direction = Sorting::DESCENDING // Sorting::ASCENDING, Sorting::DESCENDING
); // Order[]
```

###### Get Order By ID
Calls `GET /orders/{id}` and returns an Order.

```php
$client->getOrder(string $orderId); // Order
```

###### Get Order By Client Order Id
Calls `GET /orders:by_client_order_id` and returns an order by `client_order_id`. You can set `client_order_id` while creating  your orders to easily keep track of your orders.

```php
$client->getOrderByClientOrderId(string $clientOrderId); // Order
```

###### Cancel an Order
Calls `DELETE /orders/{id}` and deletes an Order.

```php
$client->cacnelOrder(string $orderId); // void
```

Raises an `AlpacaApiException` when it fails.

##### Positions API
###### Get Position
Calls `GET /positions/{symbol}` and returns a Position.

```php
$client->getOpenPosition(string $symbol); // Position
```

###### Get All Position
Calls `GET /positions` and returns an array of Position objects.

```php
$client->getOpenPositions(); // Position[]
```

##### Assets API
###### Get Assets
Calls `GET /assets/` and returns an array of Assets matching your criteria.

```php
$client->getAssets(
    string $status = '' // '', AssetStatus::ACTIVE, AssetStatus::INACTIVE
); // Asset[]
```
    
###### Get Asset Information
Calls `GET /assets/{symbol}` and returns an Asset entity.

```php
$client->getAsset(string $assetIdOrSymbol); // Asset
```
  
##### Calendar API
Calls `GET/calendar` and returns the market calendar for the selected dates

```php
$client->getCalendar(DateTime $start = null, DateTime $end = null); // Calendar[]
```

##### Data API
###### Get Bars
```php
$client->getBars(
    array $symbols, // up to 200 symbols
    string $timeFrame, // BarTimeFrame::MINUTE, BarTimeFrame::FIVE_MINUTES, BarTimeFrame::FIFTEEN_MINUTES, BarTimeFrame::DAY
    int $limit = 100,
    DateTime $start = null,
    DateTime $end = null
); // Bar[]
```

#### Tests
In order to run the tests, install the dev-dependencies (`composer install --dev`) and then run:

```bash
    vendor/bin/phpunit -c phpunit.xml.dist
```
