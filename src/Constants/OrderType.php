<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-27, 19:55.
 * @license Apache-2.0
 */

namespace RagingProdigy\Alpaca\Constants;

/**
 * Class OrderType.
 */
final class OrderType
{
    public const MARKET = 'market';
    public const LIMIT = 'limit';
    public const STOP = 'stop';
    public const STOP_LIMIT = 'stop_limit';

    /**
     * @param $status
     * @return bool
     */
    public static function isValid($status): bool
    {
        return in_array($status, [static::MARKET, static::LIMIT, static::STOP, static::STOP_LIMIT], true);
    }
}
