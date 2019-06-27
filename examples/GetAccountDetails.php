<?php
declare(strict_types=1);

/**
 * Created by: bernard <o.omonayajo@gmail.com>
 * Created on: 2019-06-27, 15:29.
 * @license Apache-2.0
 */

require __DIR__ . '/../vendor/autoload.php';

$dotEnv = Dotenv\Dotenv::create(__DIR__ . '/../');
$dotEnv->load();

$client = new \RagingProdigy\Alpaca\Client(
    new \RagingProdigy\Alpaca\Config(
        getenv('ALPACA_API_KEY'),
        getenv('ALPACA_SECRET_KEY'),
        true
    )
);

try {
    $account = $client->getAccount();
    echo "Buying Power: {$account->getBuyingPower()}\n\n";
    echo $account;
} catch (Exception $e) {
}

