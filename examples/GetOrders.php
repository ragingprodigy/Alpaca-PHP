<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-27, 19:43.
 * @license Apache-2.0
 */

use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$dotEnv = Dotenv::create(__DIR__ . '/../');
$dotEnv->load();

$client = new \RagingProdigy\Alpaca\Client(
    new \RagingProdigy\Alpaca\Config(
        getenv('ALPACA_API_KEY'),
        getenv('ALPACA_SECRET_KEY'),
        true
    )
);

$orders = $client->getOrders();

print_r($orders);
