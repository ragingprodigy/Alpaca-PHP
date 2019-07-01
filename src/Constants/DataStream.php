<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-07-01, 01:38.
 * @license Apache-2.0
 */

namespace RagingProdigy\Alpaca\Constants;

/**
 * Class DataStream.
 */
final class DataStream
{
    public const TRADE_UPDATES = 'trade_updates';
    public const ACCOUNT_UPDATES = 'account_updates';

    /**
     * @param string $stream
     * @return bool
     */
    public static function isValid(string $stream): bool
    {
        return in_array($stream, [static::TRADE_UPDATES, static::ACCOUNT_UPDATES], true);
    }
}
