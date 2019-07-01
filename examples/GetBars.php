<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-30, 16:29.
 * @license Apache-2.0
 */

use RagingProdigy\Alpaca\Constants\BarTimeFrame;

include 'header.php';

$bars = $alpacaClient->getBars(['GOOG', 'AAPL', 'AMZN'], BarTimeFrame::FIFTEEN_MINUTES, 10);

foreach ($bars as $bar) {
    echo $bar . "\n";
}
