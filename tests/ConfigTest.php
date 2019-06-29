<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-30, 00:07.
 * @license Apache-2.0
 */

namespace Tests;

use PHPUnit\Framework\TestCase;
use RagingProdigy\Alpaca\Client;
use RagingProdigy\Alpaca\Config;

/**
 * Class ConfigTest.
 */
class ConfigTest extends TestCase
{
    public function testConfigValues(): void
    {
        $client = new Client(new Config(
            'api.key',
            'api.secret',
            true,
            'base.url',
            'data.base.url',
            'polygon.base.url'
        ));

        $this->assertEquals('api.key', $client->getApiKey());
        $this->assertEquals('api.secret', $client->getApiSecret());
        $this->assertEquals('base.url', $client->getBaseUrl());
        $this->assertEquals('data.base.url', $client->getDataBaseUrl());
        $this->assertEquals('polygon.base.url', $client->getPolygonUrl());
    }

    public function testPaperTradingUrl(): void
    {
        $client = new Client(new Config(
            'api.key',
            'api.secret',
            true
        ));

        $this->assertStringContainsString('paper', $client->getBaseUrl());
    }

    public function testLiveTradingConfig(): void
    {
        $client = new Client(new Config(
            'api.key',
            'api.secret',
            false
        ));

        $this->assertStringNotContainsString('paper', $client->getBaseUrl());
    }
}
