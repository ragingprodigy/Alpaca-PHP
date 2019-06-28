<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-28, 08:14.
 * @license Apache-2.0
 */

namespace RagingProdigy\Alpaca\Traits;

use RagingProdigy\Alpaca\Entities\Position;

/**
 * Trait RetrievesPositions.
 */
trait RetrievesPositions
{
    /**
     * @return array|Position[]
     */
    public function getOpenPositions(): array
    {
       return array_map(
           static function (array $position) {
               return new Position($position);
           },
           $this->get('positions')
       );
    }

    /**
     * @param string $symbol
     * @return Position
     */
    public function getOpenPosition(string $symbol): Position
    {
        return new Position($this->get('positions/' . $symbol));
    }
}
