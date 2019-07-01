<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-07-01, 03:02.
 * @license Apache-2.0
 */

namespace RagingProdigy\Alpaca\Entities;

/**
 * Interface UpdateEvent.
 */
interface UpdateEvent
{
    public function getMessageType(): string;
}
