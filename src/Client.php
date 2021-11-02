<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-27, 14:44.
 * @license Apache-2.0
 */

namespace RagingProdigy\Alpaca;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Handler\StreamHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use RagingProdigy\Alpaca\Exceptions\AlpacaAPIException;
use RagingProdigy\Alpaca\Traits\ManagesOrders;
use RagingProdigy\Alpaca\Traits\RetrievesAccount;
use RagingProdigy\Alpaca\Traits\RetrievesAssets;
use RagingProdigy\Alpaca\Traits\RetrievesBars;
use RagingProdigy\Alpaca\Traits\RetrievesClockAndCalendar;
use RagingProdigy\Alpaca\Traits\RetrievesPositions;
use RagingProdigy\Alpaca\Traits\StreamsUpdates;

/**
 * Class Client.
 */
class Client
{
    use RetrievesAccount, ManagesOrders, RetrievesClockAndCalendar, RetrievesAssets, RetrievesPositions, RetrievesBars,
        StreamsUpdates;

    /**
     * @var string
     */
    protected string $apiKey;

    /**
     * @var string
     */
    protected string $apiSecret;

    /** @var HttpClient */
    private HttpClient $httpClient;

    /**
     * @var string
     */
    private string $baseUrl;

    /**
     * @var string
     */
    private string $dataBaseUrl;

    /**
     * Client constructor.
     * @param Config $config
     * @param ClientInterface|null $httpClient
     */
    public function __construct(Config $config, ClientInterface $httpClient = null)
    {
        $this->baseUrl = $config->getBaseUrl();
        $this->apiKey = $config->getApiKey();
        $this->apiSecret = $config->getSecretKey();

        $this->dataBaseUrl = $config->getDataBaseUrl();

        $handler = new StreamHandler();
        $stack = HandlerStack::create($handler);

        $this->httpClient = $httpClient ?? new HttpClient([
            'handler' => $stack,
            RequestOptions::ALLOW_REDIRECTS => true,
            RequestOptions::CONNECT_TIMEOUT => 15,
            RequestOptions::TIMEOUT => 30,
            RequestOptions::VERIFY => false,
        ]);
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @return string
     */
    public function getApiSecret(): string
    {
        return $this->apiSecret;
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * @return string
     */
    public function getDataBaseUrl(): string
    {
        return $this->dataBaseUrl;
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
     * @param array|null $body
     * @return array
     */
    private function sendRequest(string $method, string $endPoint, array $params = [], array $body = null): array
    {
        $request = new Request(
            $method,
            $endPoint,
            $this->requestHeaders(),
            $body ? json_encode($body) : null
        );

        try {
            $response = $this->httpClient->send($request, [RequestOptions::QUERY => $params]);

            if ($response->getStatusCode() >= 300) {
                throw new AlpacaAPIException(
                    sprintf('Request failed with code: %d', $response->getStatusCode()),
                    $response->getStatusCode());
            }

            if ('DELETE' === $method) {
                return [];
            }

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $exception) {
            throw new AlpacaAPIException($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @param string $endPoint
     * @return string
     */
    private function buildFullUrl(string $endPoint): string
    {
        return implode('/', [ $this->getBaseUrl(), $endPoint ]);
    }

    /**
     * @param string $endPoint
     * @return string
     */
    private function buildFullDataUrl(string $endPoint): string
    {
        return implode('/', [ $this->getDataBaseUrl(), $endPoint ]);
    }

    /**
     * @param string $endPoint
     * @param array $params
     * @return array
     */
    protected function get(string $endPoint, array $params = []): array
    {
        return $this->sendRequest('GET', $this->buildFullUrl($endPoint), $params);
    }

    /**
     * @param string $endPoint
     * @param array $params
     * @return array
     */
    protected function dataGet(string $endPoint, array $params = []): array
    {
        return $this->sendRequest('GET', $this->buildFullDataUrl($endPoint), $params);
    }

    /**
     * @param string $endPoint
     * @param array $body
     * @return array
     */
    protected function post(string $endPoint, array $body = []): array
    {
        return $this->sendRequest('POST', $this->buildFullUrl($endPoint), [], $body);
    }

    /**
     * @param string $endPoint
     */
    protected function delete(string $endPoint): void
    {
        $this->sendRequest('DELETE', $this->buildFullUrl($endPoint));
    }
}
