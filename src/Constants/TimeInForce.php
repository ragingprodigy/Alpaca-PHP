<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-27, 20:00.
 * @license Apache-2.0
 */

namespace RagingProdigy\Alpaca\Constants;

/**
 * Class TimeInForce.
 */
final class TimeInForce
{
    public const DAY = 'day';
    public const GTC = 'gtc';
    public const OPG = 'opg';
    public const IOC = 'ioc';
    public const FOK = 'fok';

    /**
     * @param $status
     * @return bool
     */
    public static function isValid($status): bool
    {
        return in_array($status, [static::DAY, static::GTC, static::OPG, static::IOC, static::FOK], true);
    }
}
