<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-27, 19:33.
 * @license Apache-2.0
 */

namespace RagingProdigy\Alpaca\Constants;

/**
 * Class OrderStatus.
 */
final class OrderStatus
{
    public const OPEN = 'open';
    public const CLOSED = 'closed';
    public const ALL = 'all';

    /**
     * @param $status
     * @return bool
     */
    public static function isValid($status): bool
    {
        return in_array($status, [static::OPEN, static::CLOSED, static::ALL], true);
    }
}
