<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-28, 07:55.
 * @license Apache-2.0
 */

namespace RagingProdigy\Alpaca\Traits;

use RagingProdigy\Alpaca\Entities\Clock;

/**
 * Trait GetsClock.
 */
trait GetsClock
{
    /**
     * @return Clock
     */
    public function getClock(): Clock
    {
        return new Clock($this->get('clock'));
    }
}
