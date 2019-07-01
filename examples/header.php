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

$alpacaClient = new Client(new Config('api.key', 'secret.key'));
