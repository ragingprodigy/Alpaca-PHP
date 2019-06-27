<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-27, 14:51.
 * @license Apache-2.0
 */

namespace RagingProdigy\Alpaca;

/**
 * Class Config.
 */
class Config
{
    private $baseUrl;

    private $dataBaseUrl;

    private $polygonBaseUrl;

    private $apiKey;

    private $secretKey;

    /**
     * Config constructor.
     * @param string $apiKey
     * @param string $secretKey
     * @param bool $paperTrading
     * @param string $baseUrl
     * @param string $dataBaseUrl
     * @param string $polygonBaseUrl
     */
    public function __construct(
        string $apiKey,
        string $secretKey,
        $paperTrading = true,
        string $baseUrl = null,
        string $dataBaseUrl = null,
        string $polygonBaseUrl = null
    ) {
        $this->apiKey = $apiKey;
        $this->secretKey = $secretKey;

        $this->baseUrl = $baseUrl ?? $paperTrading ? 'https://paper-api.alpaca.markets' : 'https://api.alpaca.markets';
        $this->dataBaseUrl = $dataBaseUrl ?? 'https://data.alpaca.markets';
        $this->polygonBaseUrl = $polygonBaseUrl ?? 'https://api.polygon.io';
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
     * @return string
     */
    public function getPolygonBaseUrl(): string
    {
        return $this->polygonBaseUrl;
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
    public function getSecretKey(): string
    {
        return $this->secretKey;
    }
}
