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
    /** @var string string */
    private $baseUrl;

    /** @var string string */
    private $dataBaseUrl;

    /** @var string string */
    private $apiKey;

    /** @var string string */
    private $secretKey;

    /**
     * Config constructor.
     * @param string $apiKey
     * @param string $secretKey
     * @param bool $paperTrading
     * @param string $baseUrl
     * @param string $dataBaseUrl
     */
    public function __construct(
        string $apiKey,
        string $secretKey,
        $paperTrading = true,
        string $baseUrl = null,
        string $dataBaseUrl = null
    ) {
        $this->apiKey = $apiKey;
        $this->secretKey = $secretKey;

        if ($paperTrading) {
            $this->baseUrl = $baseUrl ?? 'https://paper-api.alpaca.markets/v2';
        } else {
            $this->baseUrl = $baseUrl ?? 'https://api.alpaca.markets/v2';
        }

        $this->dataBaseUrl = $dataBaseUrl ?? 'https://data.alpaca.markets/v1';
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
