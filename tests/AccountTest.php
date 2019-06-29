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
use RagingProdigy\Alpaca\Entities\Account;
use Tests\Utils\Factory;

/**
 * Class AccountTest.
 */
class AccountTest extends ClientTestCase
{

    public function testAccountRetrieval(): void
    {
        $account = Factory::for(Account::class);

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
