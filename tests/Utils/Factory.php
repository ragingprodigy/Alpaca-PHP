<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-30, 04:44.
 * @license Apache-2.0
 */

namespace Tests\Utils;

use RuntimeException;

/**
 * Class Factory.
 */
class Factory
{
    public static $factoryRepo = [];

    /**
     * @param string $className
     * @param int $instances
     * @return array|mixed
     */
    public static function for(string $className, int $instances = 1)
    {
        $output = [];

        while ($instances > 0) {
            if (isset(static::$factoryRepo[$className])) {
                $faker = \Faker\Factory::create();
                /** @noinspection PhpUndefinedVariableInspection */
                $output[] = static::$factoryRepo[$className]($faker);
            } else {
                throw new RuntimeException(sprintf('No Factory defined for Class Named: %s', $className));
            }

            --$instances;
        }

        return count($output) === 1 ? $output[0] : $output;
    }
}
