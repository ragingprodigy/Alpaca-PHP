<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-30, 00:30.
 * @license Apache-2.0
 */

namespace Tests;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;

/**
 * Class AccountTest.
 */
class AccountTest extends ClientTestCase
{

    public function testAccountRetrieval(): void
    {
        $account = [
            'id' => '904837e3-3b76-47ec-b432-046db621571b',
            'status' => 'ACTIVE',
            'currency' => 'USD',
            'buying_power' => '0.0',
            'cash' => '1000.00',
            'portfolio_value' => '5000.00',
            'pattern_day_trader' => false,
            'trade_suspended_by_user' => false,
            'trading_blocked' => false,
            'transfers_blocked' => false,
            'account_blocked' => false,
            'created_at' => '2018-10-01T13:35:25Z',
            'shorting_enabled' => true,
            'multiplier' => '2',
            'long_market_value' => '7000.00',
            'short_market_value' => '-3000.00',
            'equity' => '5000.00',
            'last_equity' => '5000.00',
            'initial_margin' => '5000.00',
            'maintenance_margin' => '3000.00',
            'daytrade_count' => 0,
            'sma' => '0.0',
        ];

        $expectedRequest = new Request('GET', $this->fullUrl('account'), $this->requestHeaders());

        $this->httpClient->expects($this->once())
            ->method('send')
            ->with($expectedRequest, [ RequestOptions::QUERY => []])
            ->willReturn($this->responseForParams($account));

        $apiAccount = $this->alpacaClient->getAccount();
        $this->assertEquals($account, $apiAccount->jsonSerialize());
    }
}
