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
use RagingProdigy\Alpaca\Entities\Calendar;
use RagingProdigy\Alpaca\Entities\Clock;
use RagingProdigy\Alpaca\Entities\Order;
use RagingProdigy\Alpaca\Entities\Position;

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
        $dateFormat = 'Y-m-dTH:i:aZ';

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

        custom_factory(Clock::class, static function (Faker $faker) use ($dateFormat) {
            return [
                'timestamp' => $faker->dateTimeThisMonth->format($dateFormat),
                'is_open' => $faker->randomElement([true, false]),
                'next_open' => $faker->dateTimeThisMonth->format($dateFormat),
                'next_close' => $faker->dateTimeThisMonth->format($dateFormat),
            ];
        });

        custom_factory(Calendar::class, static function (Faker $faker) use ($dateFormat) {
            return [
                'date' => $faker->dateTimeThisMonth->format($dateFormat),
                'open' => '09:30',
                'close' => '16:00',
            ];
        });

        custom_factory(Position::class, static function (Faker $faker) {
            return [
                'asset_id' => $faker->uuid,
                'symbol' => $faker->word,
                'exchange' => $faker->word,
                'asset_class' => $faker->word,
                'avg_entry_price' => (string) $faker->randomFloat(1),
                'qty' => (string) $faker->randomNumber(2),
                'side' => $faker->word,
                'market_value' => (string) $faker->randomFloat(1),
                'cost_basis' => (string) $faker->randomFloat(1),
                'unrealized_pl' => (string) $faker->randomFloat(1),
                'unrealized_plpc' => (string) $faker->randomFloat(2),
                'unrealized_intraday_pl' => (string) $faker->randomFloat(1),
                'unrealized_intraday_plpc' => (string) $faker->randomFloat(5),
                'current_price' => (string) $faker->randomFloat(1),
                'lastday_price' => (string) $faker->randomFloat(1),
                'change_today' => (string) $faker->randomFloat(4),
            ];
        });

        custom_factory(Order::class, static function (Faker $faker) use ($dateFormat) {
            return [
                'id' => $faker->uuid,
                'client_order_id' => $faker->uuid,
                'created_at' => $faker->dateTimeThisMonth->format($dateFormat),
                'updated_at' => $faker->dateTimeThisMonth->format($dateFormat),
                'submitted_at' => $faker->dateTimeThisMonth->format($dateFormat),
                'filled_at' => $faker->dateTimeThisMonth->format($dateFormat),
                'expired_at' => $faker->dateTimeThisMonth->format($dateFormat),
                'canceled_at' => $faker->dateTimeThisMonth->format($dateFormat),
                'failed_at' => $faker->dateTimeThisMonth->format($dateFormat),
                'asset_id' => $faker->uuid,
                'symbol' => $faker->word,
                'asset_class' => $faker->word,
                'qty' => (string) $faker->randomNumber(2),
                'filled_qty' => '0',
                'type' => $faker->randomElement(['market', 'limit', 'stop', 'stop_limit']),
                'side' => $faker->randomElement(['buy', 'sell']),
                'time_in_force' => $faker->randomElement(['day', 'gtc', 'opg', 'ioc', 'fok']),
                'limit_price' => (string) $faker->randomFloat(2),
                'stop_price' => (string) $faker->randomFloat(2),
                'filled_average_price' => (string) $faker->randomFloat(2),
                'status' => $faker->randomElement(['accepted', 'pending_new', 'accepted_for_bidding', 'rejected']),
                'extended_hours' => $faker->randomElement([true, false]),
            ];
        });
        
        custom_factory(Account::class, static function (Faker $faker) use ($dateFormat) {
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
                'created_at' => $faker->dateTimeThisMonth->format($dateFormat),
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
