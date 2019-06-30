# Alpaca-PHP
Alpaca Trade API for PHP [![Build Status](https://travis-ci.org/ragingprodigy/Alpaca-PHP.svg?branch=master)](https://travis-ci.org/ragingprodigy/Alpaca-PHP)

## Overview
This is a PHP Client for <a href="https://alpaca.markets/">Alpaca</a> (<a href="https://docs.alpaca.markets/api-documentation/web-api/">General Alpaca API Documentation</a>).  Alpaca API lets you build and trade with real-time market data for free.

### Table of Contents
1. [Installation](#installation)
2. [Basic Usage](#basic-usage)
3. [Full Scale Example](#full-scale-example)
4. [Alpaca API](#alpaca-api)
5. [Simple Polygon Example](#simple-polygon-example)
6. [Polygon API](#polygon-api)

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
use Ragingprodigy\Alpaca\Client;
use Ragingprodigy\Alpaca\Config;

$alpacaClient = new Client(new Config('api.key', 'secret.key'));

$account = $alpacaAccount

```
