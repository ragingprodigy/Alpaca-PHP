<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-27, 15:29.
 * @license Apache-2.0
 */

include 'header.php';

try {
    $account = $client->getAccount();
    echo "Buying Power: {$account->getBuyingPower()}";
} catch (Exception $e) {
}

