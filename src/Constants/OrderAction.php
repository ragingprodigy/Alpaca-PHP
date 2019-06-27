<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-27, 19:59.
 * @license Apache-2.0
 */

namespace RagingProdigy\Alpaca\Constants;

/**
 * Class OrderAction.
 */
final class OrderAction
{
    public const BUY = 'buy';
    public const SELL = 'sell';

    /**
     * @param $status
     * @return bool
     */
    public static function isValid($status): bool
    {
        return in_array($status, [static::BUY, static::SELL], true);
    }
}
