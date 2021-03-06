<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-28, 07:55.
 * @license Apache-2.0
 */

namespace RagingProdigy\Alpaca\Traits;

use DateTime;
use RagingProdigy\Alpaca\Entities\Calendar;
use RagingProdigy\Alpaca\Entities\Clock;

/**
 * Trait GetsClock.
 */
trait RetrievesClockAndCalendar
{
    /**
     * @return Clock
     */
    public function getClock(): Clock
    {
        return new Clock($this->get('clock'));
    }

    /**
     * @param DateTime $start
     * @param DateTime $end
     * @return array|Calendar[]
     */
    public function getCalendar(DateTime $start = null, DateTime $end = null): array
    {
        return array_map(static function (array $calendar) {
            return new Calendar($calendar);
        }, $this->get(
            'calendar',
            [
                'start' => $start ? $start->format(DATE_ATOM) : null,
                'end' => $end ? $end->format(DATE_ATOM) : null
            ]
        ));
    }
}
