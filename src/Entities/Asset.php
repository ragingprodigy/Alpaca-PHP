<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-28, 07:43.
 * @license Apache-2.0
 */

namespace RagingProdigy\Alpaca\Entities;

use JsonSerializable;

/**
 * Class Asset.
 */
class Asset implements JsonSerializable
{

    /** @var array */
    private array $raw;

    /** @var string */
    private string $id;
    /** @var string */
    private string $assetClass;
    /** @var string */
    private string $exchange;
    /** @var string */
    private string $symbol;
    /** @var string */
    private string $status;
    /** @var bool */
    private bool $tradable;
    /** @var bool */
    private bool $marginable;
    /** @var bool */
    private bool $shortable;
    /** @var bool */
    private bool $easyToBorrow;

    /**
     * Asset constructor.
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->raw = $params;

        $this->id = $params['id'];
        $this->assetClass = $params['asset_class'] ?? $params['class'] ?? '';
        $this->exchange = $params['exchange'];
        $this->symbol = $params['symbol'];
        $this->status = $params['status'];

        $this->tradable = $params['tradable'];
        $this->marginable = $params['marginable'];
        $this->shortable = $params['shortable'];
        $this->easyToBorrow = $params['easy_to_borrow'];
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAssetClass(): string
    {
        return $this->assetClass;
    }

    /**
     * @return string
     */
    public function getExchange(): string
    {
        return $this->exchange;
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return bool
     */
    public function isTradable(): bool
    {
        return $this->tradable;
    }

    /**
     * @return bool
     */
    public function isMarginable(): bool
    {
        return $this->marginable;
    }

    /**
     * @return bool
     */
    public function isShortable(): bool
    {
        return $this->shortable;
    }

    /**
     * @return bool
     */
    public function isEasyToBorrow(): bool
    {
        return $this->easyToBorrow;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return '' . json_encode($this->jsonSerialize());
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize(): array
    {
        return $this->raw;
    }
}
