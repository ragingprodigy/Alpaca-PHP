<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-27, 14:44.
 * @license Apache-2.0
 */

namespace RagingProdigy\Alpaca;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\StreamHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use RagingProdigy\Alpaca\Traits\ManagesOrders;
use RagingProdigy\Alpaca\Traits\RetrievesAccount;
use RuntimeException;

/**
 * Class Client.
 */
class Client
{
    use RetrievesAccount, ManagesOrders;

    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $apiSecret;

    /** @var HttpClient */
    private $httpClient;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var string
     */
    private $polygonUrl;

    /**
     * @var string
     */
    private $dataBaseUrl;

    /**
     * Client constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->baseUrl = $config->getBaseUrl();
        $this->apiKey = $config->getApiKey();
        $this->apiSecret = $config->getSecretKey();

        $this->polygonUrl = $config->getPolygonBaseUrl();
        $this->dataBaseUrl = $config->getDataBaseUrl();

        $handler = new StreamHandler();
        $stack = HandlerStack::create($handler);

        $this->httpClient = new HttpClient([
            'handler' => $stack,
            RequestOptions::ALLOW_REDIRECTS => true,
            RequestOptions::CONNECT_TIMEOUT => 15,
            RequestOptions::TIMEOUT => 30,
            RequestOptions::VERIFY => false,
        ]);
    }

    /**
     * @return array
     */
    private function requestHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'APCA-API-KEY-ID' => $this->apiKey,
            'APCA-API-SECRET-KEY' => $this->apiSecret,
        ];
    }

    /**
     * @param string $method
     * @param string $endPoint
     * @param array $params
     * @param array $body
     * @return array
     * @throws GuzzleException
     */
    private function sendRequest(string $method, string $endPoint, array $params = [], array $body = null): array
    {
        $request = new Request(
            $method,
            $this->buildFullUrl($endPoint),
            $this->requestHeaders(),
            json_encode($body)
        );

        $response = $this->httpClient->send($request, [ RequestOptions::QUERY => $params]);

        if ($response->getStatusCode() < 300) {
            return json_decode($response->getBody()->getContents(), true);
        }

        throw new RuntimeException('Request failed with code: ' . $response->getStatusCode());
    }

    /**
     * @param string $endPoint
     * @return string
     */
    private function buildFullUrl($endPoint): string
    {
        return implode('/', [ $this->baseUrl, 'v2', $endPoint ]);
    }

    /**
     * @param string $endPoint
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    protected function get(string $endPoint, array $params = []): array
    {
        return $this->sendRequest('GET', $endPoint, $params);
    }

    /**
     * @param string $endPoint
     * @param array $body
     * @return array
     * @throws GuzzleException
     */
    protected function post(string $endPoint, array $body = []): array
    {
        return $this->sendRequest('POST', $endPoint, [], $body);
    }

    /**
     * @param string $endPoint
     * @param array $body
     * @return array
     * @throws GuzzleException
     */
    protected function put(string $endPoint, array $body = []): array
    {
        return $this->sendRequest('PUT', $endPoint, [], $body);
    }

    /**
     * @param string $endPoint
     * @return array
     * @throws GuzzleException
     */
    protected function delete(string $endPoint): array
    {
        return $this->sendRequest('DELETE', $endPoint);
    }
}