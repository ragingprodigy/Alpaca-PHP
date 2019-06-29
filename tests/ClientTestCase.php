<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-30, 01:00.
 * @license Apache-2.0
 */

namespace Tests;

use Faker\Generator as Faker;
use GuzzleHttp\Client as HttpClient;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use RagingProdigy\Alpaca\Client;
use RagingProdigy\Alpaca\Config;
use RagingProdigy\Alpaca\Entities\Account;
use RagingProdigy\Alpaca\Entities\Asset;

/**
 * Class ClientTestCase.
 */
abstract class ClientTestCase extends TestCase
{

    /** @var MockObject|HttpClient */
    protected $httpClient;

    /** @var Client */
    protected $alpacaClient;

    /**
     * @param string $apiKey
     * @param string $apiSecret
     * @param string $baseUrl
     */
    protected function setUp($apiKey = 'key', $apiSecret = 'secret', $baseUrl = 'http://mock.url'): void
    {
        parent::setUp();

        $this->httpClient = $this->getMockBuilder(HttpClient::class)->getMock();
        $this->alpacaClient = new Client(
            new Config($apiKey, $apiSecret, true, $baseUrl),
            $this->httpClient
        );

        $this->createEntityFactories();
    }

    /**
     * @param string $path
     * @return string
     */
    protected function fullUrl(string $path): string
    {
        return $this->alpacaClient->getBaseUrl() . '/v2/' . $path;
    }

    /**
     * @return array
     */
    protected function requestHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'APCA-API-KEY-ID' => $this->alpacaClient->getApiKey(),
            'APCA-API-SECRET-KEY' => $this->alpacaClient->getApiSecret(),
        ];
    }

    /**
     * @param mixed $responseBody
     * @param int $statusCode
     * @return MockObject|ResponseInterface
     */
    protected function responseForParams($responseBody = null, int $statusCode = 200)
    {
        $responseMock = $this->getMockBuilder(ResponseInterface::class)->getMock();

        $responseMock->method('getStatusCode')->willReturn($statusCode);

        if ($responseBody) {
            $stream = $this->getMockBuilder(StreamInterface::class)->getMock();
            $stream->expects($this->once())
                ->method('getContents')
                ->willReturn(json_encode($responseBody));

            $responseMock->expects($this->once())
                ->method('getBody')
                ->willReturn($stream);
        }

        return $responseMock;
    }

    private function createEntityFactories(): void
    {
        custom_factory(Asset::class, static function (Faker $faker) {
            return  [
                'id' => $faker->uuid,
                'asset_class' => $faker->word,
                'exchange' => $faker->word,
                'symbol' => $faker->word,
                'status' => $faker->randomElement(['active', 'inactive']),
                'tradable' => $faker->randomElement([true, false]),
                'marginable' => $faker->randomElement([true, false]),
                'shortable' => $faker->randomElement([true, false]),
                'easy_to_borrow' => $faker->randomElement([true, false]),
            ];
        });

        custom_factory(Account::class, static function (Faker $faker) {
            return  [
                'id' => $faker->uuid,
                'status' => 'ACTIVE',
                'currency' => $faker->currencyCode,
                'buying_power' => (string) $faker->randomFloat(2),
                'cash' => (string) $faker->randomFloat(2),
                'portfolio_value' => (string) $faker->randomFloat(2),
                'pattern_day_trader' => $faker->randomElement([true, false]),
                'trade_suspended_by_user' => $faker->randomElement([true, false]),
                'trading_blocked' => $faker->randomElement([true, false]),
                'transfers_blocked' => $faker->randomElement([true, false]),
                'account_blocked' => $faker->randomElement([true, false]),
                'created_at' => $faker->dateTimeThisMonth->format('Y-m-dTH:i:aZ'),
                'shorting_enabled' => $faker->randomElement([true, false]),
                'multiplier' => (string) $faker->randomElement([1,2,3,4]),
                'long_market_value' => (string) $faker->randomFloat(2),
                'short_market_value' => (string) $faker->randomFloat(2, -100000),
                'equity' => (string) $faker->randomFloat(2),
                'last_equity' => (string) $faker->randomFloat(2),
                'initial_margin' => (string) $faker->randomFloat(2),
                'maintenance_margin' => (string) $faker->randomFloat(2),
                'daytrade_count' => $faker->randomNumber(),
                'sma' => (string) $faker->randomFloat(2),
            ];
        });
    }
}
