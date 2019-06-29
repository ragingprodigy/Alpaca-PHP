<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-30, 06:30.
 * @license Apache-2.0
 */

namespace Tests;

use GuzzleHttp\Psr7\Request;
use RagingProdigy\Alpaca\Entities\Position;
use Tests\Utils\Factory;

/**
 * Class PositionsTest.
 */
class PositionsTest extends ClientTestCase
{
    public function testPositionsRetrieval(): void
    {
        $rawData = Factory::for(Position::class, 10);
        $response = $this->responseForParams($rawData);

        $request = new Request('GET', $this->fullUrl('positions'), $this->requestHeaders());
        $this->httpClient->expects($this->once())->method('send')
            ->with($request, ['query' => []])->willReturn($response);

        $positions = $this->alpacaClient->getOpenPositions();

        $this->assertCount(10, $positions);

        foreach ($positions as $position) {
            $this->assertIsString('' . $position);
            $this->assertPositionIsValid($position);
        }
    }

    public function testSinglePositionRetrieval(): void
    {
        $rawData = Factory::for(Position::class);
        $response = $this->responseForParams($rawData);

        $request = new Request('GET', $this->fullUrl('positions/' . $rawData['symbol']), $this->requestHeaders());
        $this->httpClient->expects($this->once())->method('send')
            ->with($request, ['query' => []])->willReturn($response);

        $position = $this->alpacaClient->getOpenPosition($rawData['symbol']);

        $this->assertEquals($rawData['asset_id'], $position->getAssetId());
        $this->assertEquals($rawData['asset_class'], $position->getAssetClass());
        $this->assertEquals($rawData['symbol'], $position->getSymbol());
        $this->assertEquals($rawData['exchange'], $position->getExchange());
        $this->assertEquals($rawData['qty'], $position->getQuantity());
        $this->assertEquals($rawData['side'], $position->getSide());

        $this->assertEquals($rawData['market_value'], $position->getMarketValue());
        $this->assertEquals($rawData['cost_basis'], $position->getCostBasis());
        $this->assertEquals($rawData['unrealized_pl'], $position->getUnrealizedPl());
        $this->assertEquals($rawData['unrealized_plpc'], $position->getUnrealizedPlPc());
        $this->assertEquals($rawData['unrealized_intraday_pl'], $position->getUnrealizedIntradayPl());
        $this->assertEquals($rawData['unrealized_intraday_plpc'], $position->getUnrealizedIntradayPlPc());
        $this->assertEquals($rawData['current_price'], $position->getCurrentPrice());
        $this->assertEquals($rawData['lastday_price'], $position->getLastdayPrice());
        $this->assertEquals($rawData['change_today'], $position->getChangeToday());
    }

    private function assertPositionIsValid(Position $position): void
    {
        $this->assertIsString($position->getAssetId());
        $this->assertIsString($position->getAssetClass());
        $this->assertIsString($position->getSymbol());
        $this->assertIsString($position->getExchange());

        $this->assertIsNumeric($position->getAverageEntryPrice());
        $this->assertIsNumeric($position->getQuantity());
        $this->assertIsString($position->getSide());

        $this->assertIsNumeric($position->getMarketValue());
        $this->assertIsNumeric($position->getCostBasis());
        $this->assertIsNumeric($position->getUnrealizedPl());
        $this->assertIsNumeric($position->getUnrealizedPlPc());
        $this->assertIsNumeric($position->getUnrealizedIntradayPl());
        $this->assertIsNumeric($position->getUnrealizedIntradayPlPc());

        $this->assertIsNumeric($position->getCurrentPrice());
        $this->assertIsNumeric($position->getLastdayPrice());
        $this->assertIsNumeric($position->getChangeToday());
    }
}
