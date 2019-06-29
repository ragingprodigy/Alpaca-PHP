<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-30, 05:14.
 * @license Apache-2.0
 */

use Tests\Utils\Factory;

require __DIR__ . '/../vendor/autoload.php';

if (!function_exists('custom_factory')) {
    function custom_factory($className, Closure $closure) {
        Factory::$factoryRepo[$className] = $closure;
    }
}
