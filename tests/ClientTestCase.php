<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-30, 01:00.
 * @license Apache-2.0
 */

namespace Tests;

use GuzzleHttp\Client as HttpClient;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use RagingProdigy\Alpaca\Client;
use RagingProdigy\Alpaca\Config;

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
        $stream = $this->getMockBuilder(StreamInterface::class)->getMock();
        $stream->expects($this->once())
            ->method('getContents')
            ->willReturn(json_encode($responseBody));

        $responseMock = $this->getMockBuilder(ResponseInterface::class)->getMock();

        $responseMock->method('getStatusCode')->willReturn($statusCode);
        $responseMock->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        return $responseMock;
    }
}
