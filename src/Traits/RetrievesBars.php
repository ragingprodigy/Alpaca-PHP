<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-30, 16:19.
 * @license Apache-2.0
 */

namespace RagingProdigy\Alpaca\Traits;

use DateTime;
use RagingProdigy\Alpaca\Constants\BarTimeFrame;
use RagingProdigy\Alpaca\Entities\Bar;
use RagingProdigy\Alpaca\Exceptions\InvalidApiUsageException;

/**
 * Trait RetrievesBars.
 */
trait RetrievesBars
{

    /**
     * @param array $symbols
     * @param string $timeFrame
     * @param int $limit
     * @param DateTime|null $start
     * @param DateTime|null $end
     * @return array|Bar[]
     */
    public function getBars(
        array $symbols,
        string $timeFrame,
        int $limit = 100,
        DateTime $start = null,
        DateTime $end = null
    ): array {
        if (200 < count($symbols)) {
            throw new InvalidApiUsageException('Maximum number of Symbols exceeded. Only 200 allowed');
        }

        if (!BarTimeFrame::isValid($timeFrame)) {
            throw new InvalidApiUsageException(sprintf('%s is not a valid TimeFrame', $timeFrame));
        }

        if ($limit > 1000 || 0 >= $limit) {
            $limit = 1000;
        }

        $barsResponse = $this->dataGet("bars/$timeFrame", [
            'symbols' => implode(',', $symbols),
            'limit' => $limit,
            'start' => $start?->format(DATE_ATOM),
            'end' => $end?->format(DATE_ATOM),
        ]);

        $result = [];

        foreach ($barsResponse as $symbol => $bars) {
            foreach ($bars as $bar) {
                $result[] = new Bar($symbol, $bar);
            }
        }

        return $result;
    }
}
