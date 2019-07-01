<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-27, 15:29.
 * @license Apache-2.0
 */

include 'header.php';

$account = $alpacaClient->getAccount();
echo "Buying Power: {$account->getBuyingPower()}";

