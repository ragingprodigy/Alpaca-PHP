<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-30, 01:22.
 * @license Apache-2.0
 */

namespace Tests;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use RagingProdigy\Alpaca\Entities\Asset;
use RagingProdigy\Alpaca\Exceptions\InvalidApiUsageException;
use RuntimeException;
use Tests\Utils\Factory;

/**
 * Class AssetsTest.
 */
class AssetsTest extends ClientTestCase
{
    public function testAssetRetrievalWithInvalidStatus(): void
    {
        $this->expectException(InvalidApiUsageException::class);
        $this->expectExceptionMessage('Please provide a valid asset status');

        $this->alpacaClient->getAssets('invalid.status');
    }

    public function testAllAssetsRetrieval(): void
    {
        $assetsPayload = Factory::for(Asset::class, 25);

        $response = $this->responseForParams($assetsPayload);
        $request = new Request('GET', $this->fullUrl('assets'), $this->requestHeaders());

        $this->httpClient->expects($this->once())->method('send')
            ->with($request,  [ RequestOptions::QUERY => ['status' => '']])->willReturn($response);

        $assets = $this->alpacaClient->getAssets();

        foreach ($assets as $asset) {
            $this->assertAssetIsValid($asset);
        }
    }

    public function testAssetNotFound(): void
    {
        $response = $this->responseForParams(null, 404);
        $request = new Request('GET', $this->fullUrl('assets/some.id'), $this->requestHeaders());

        $this->httpClient->expects($this->once())->method('send')
            ->with($request,  [ RequestOptions::QUERY => []])->willReturn($response);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Request failed with code: 404');
        $this->expectExceptionCode(404);

        $this->alpacaClient->getAsset('some.id');
    }

    public function testSingleAssetRetrieval(): void
    {
        $rawAsset = Factory::for(Asset::class);

        $response = $this->responseForParams($rawAsset);
        $request = new Request('GET', $this->fullUrl('assets/' . $rawAsset['id']), $this->requestHeaders());

        $this->httpClient->expects($this->once())->method('send')
            ->with($request,  [ RequestOptions::QUERY => []])->willReturn($response);

        $asset = $this->alpacaClient->getAsset($rawAsset['id']);

        $this->assertAssetIsValid($asset);

        $this->assertEquals($rawAsset['id'], $asset->getId());
        $this->assertEquals($rawAsset['status'], $asset->getStatus());
        $this->assertEquals($rawAsset['asset_class'], $asset->getAssetClass());
        $this->assertEquals($rawAsset['exchange'], $asset->getExchange());
        $this->assertEquals($rawAsset['symbol'], $asset->getSymbol());

        $this->assertEquals($rawAsset['easy_to_borrow'], $asset->isEasyToBorrow());
        $this->assertEquals($rawAsset['marginable'], $asset->isMarginable());
        $this->assertEquals($rawAsset['tradable'], $asset->isTradable());
        $this->assertEquals($rawAsset['shortable'], $asset->isShortable());
    }

    /**
     * @param Asset $asset
     */
    private function assertAssetIsValid(Asset $asset): void
    {
        $this->assertIsString($asset->getId());
        $this->assertIsString($asset->getStatus());
        $this->assertIsString($asset->getAssetClass());
        $this->assertIsString($asset->getExchange());
        $this->assertIsString($asset->getSymbol());

        $this->assertIsBool($asset->isEasyToBorrow());
        $this->assertIsBool($asset->isMarginable());
        $this->assertIsBool($asset->isShortable());
        $this->assertIsBool($asset->isTradable());

        $this->assertIsArray($asset->jsonSerialize());
    }
}
