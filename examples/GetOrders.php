<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-27, 19:43.
 * @license Apache-2.0
 */

include 'header.php';

$orders = $client->getOrders(\RagingProdigy\Alpaca\Constants\OrderStatus::ALL);

foreach ($orders as $order) {
    echo $order . "\n";
}

try {
    $singleOrder = $client->getOrder('88ca1d07-66ff-42f8-b363-7158f1d32022');
    echo $singleOrder;
} catch (Exception $e) {
}
