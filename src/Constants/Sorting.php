<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-27, 19:35.
 * @license Apache-2.0
 */

namespace RagingProdigy\Alpaca\Constants;

/**
 * Class Sorting.
 */
final class Sorting
{
    public const ASCENDING = 'asc';
    public const DESCENDING = 'desc';


    /**
     * @param $status
     * @return bool
     */
    public static function isValid($status): bool
    {
        return in_array($status, [static::ASCENDING, static::DESCENDING], true);
    }
}
