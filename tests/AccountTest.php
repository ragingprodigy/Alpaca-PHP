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
            'id' => 'id',
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
        $this->assertEquals(json_encode($account), $apiAccount);

        $this->assertEquals($account['id'], $apiAccount->getId());
        $this->assertEquals($account['status'], $apiAccount->getStatus());
        $this->assertEquals($account['currency'], $apiAccount->getCurrency());
        $this->assertEquals($account['buying_power'], $apiAccount->getBuyingPower());
        $this->assertEquals($account['cash'], $apiAccount->getCash());
        $this->assertEquals($account['portfolio_value'], $apiAccount->getPortfolioValue());
        $this->assertEquals($account['pattern_day_trader'], $apiAccount->isPatternDayTrader());
        $this->assertEquals($account['trade_suspended_by_user'], $apiAccount->isTradeSuspendedByUser());
        $this->assertEquals($account['trading_blocked'], $apiAccount->isTradingBlocked());
        $this->assertEquals($account['transfers_blocked'], $apiAccount->isTransfersBlocked());
        $this->assertEquals($account['account_blocked'], $apiAccount->isAccountBlocked());
        $this->assertEquals($account['created_at'], $apiAccount->getCreatedAt());
        $this->assertEquals($account['shorting_enabled'], $apiAccount->isShortingEnabled());
        $this->assertEquals($account['multiplier'], $apiAccount->getMultiplier());
        $this->assertEquals($account['long_market_value'], $apiAccount->getLongMarketValue());
        $this->assertEquals($account['short_market_value'], $apiAccount->getShortMarketValue());
        $this->assertEquals($account['equity'], $apiAccount->getEquity());
        $this->assertEquals($account['last_equity'], $apiAccount->getLastEquity());
        $this->assertEquals($account['initial_margin'], $apiAccount->getInitialMargin());
        $this->assertEquals($account['maintenance_margin'], $apiAccount->getMaintenanceMargin());
        $this->assertEquals($account['daytrade_count'], $apiAccount->getDaytradeCount());
        $this->assertEquals($account['sma'], $apiAccount->getSma());
    }
}
