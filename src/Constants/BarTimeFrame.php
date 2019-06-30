<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-30, 16:03.
 * @license Apache-2.0
 */

namespace RagingProdigy\Alpaca\Constants;

/**
 * Class BarTimeFrame.
 */
final class BarTimeFrame
{
    public const MINUTE = 'minute';
    public const FIVE_MINUTES = '5Min';
    public const FIFTEEN_MINUTES = '15Min';
    public const DAY = 'day';

    /**
     * @param string $timeFrame
     * @return bool
     */
    public static function isValid(string $timeFrame): bool
    {
        return in_array($timeFrame, [static::MINUTE, static::FIVE_MINUTES, static::FIFTEEN_MINUTES, static::DAY], true);
    }
}
