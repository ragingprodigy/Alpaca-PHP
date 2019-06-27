<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-28, 02:37.
 * @license Apache-2.0
 */

namespace RagingProdigy\Alpaca\Entities;

use JsonSerializable;

/**
 * Class Position.
 */
class Position implements JsonSerializable
{

    private $raw;

    /** @var string */
    private $assetId;
    /** @var string */
    private $symbol;
    /** @var string */
    private $exchange;
    /** @var string */
    private $assetClass;
    /** @var double */
    private $averageEntryPrice;
    /** @var int */
    private $quantity;
    /** @var string */
    private $side;
    /** @var double */
    private $marketValue;
    /** @var double */
    private $costBasis;
    /** @var double */
    private $unrealizedPl;
    /** @var double */
    private $unrealizedPlPc;
    /** @var double */
    private $unrealizedIntradayPl;
    /** @var double */
    private $unrealizedIntradayPlPc;
    /** @var double */
    private $currentPrice;
    /** @var double */
    private $lastdayPrice;
    /** @var double */
    private $changeToday;

    public function __construct(array $params)
    {
        $this->raw = $params;

        $this->assetId = $params['asset_id'];
        $this->assetClass = $params['asset_class'];
        $this->symbol = $params['symbol'];
        $this->exchange = $params['exchange'];
        $this->averageEntryPrice = (double) $params['avg_entry_price'];
        $this->quantity = (int) $params['qty'];
        $this->side = $params['side'];

        $this->marketValue = (double) $params['market_value'];
        $this->costBasis = (double) $params['cost_basis'];
        $this->unrealizedPl = (double) $params['unrealized_pl'];
        $this->unrealizedPlPc = (double) $params['unrealized_plpc'];
        $this->unrealizedIntradayPl = (double) $params['unrealized_intraday_pl'];
        $this->unrealizedIntradayPlPc = (double) $params['unrealized_intraday_plpc'];
        $this->currentPrice = (double) $params['current_price'];
        $this->lastdayPrice = (double) $params['lastday_price'];
        $this->changeToday = (double) $params['change_today'];
    }

    /**
     * @return string
     */
    public function getAssetId(): string
    {
        return $this->assetId;
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
    public function getExchange(): string
    {
        return $this->exchange;
    }

    /**
     * @return string
     */
    public function getAssetClass(): string
    {
        return $this->assetClass;
    }

    /**
     * @return float
     */
    public function getAverageEntryPrice(): float
    {
        return $this->averageEntryPrice;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return string
     */
    public function getSide(): string
    {
        return $this->side;
    }

    /**
     * @return float
     */
    public function getMarketValue(): float
    {
        return $this->marketValue;
    }

    /**
     * @return float
     */
    public function getCostBasis(): float
    {
        return $this->costBasis;
    }

    /**
     * @return float
     */
    public function getUnrealizedPl(): float
    {
        return $this->unrealizedPl;
    }

    /**
     * @return float
     */
    public function getUnrealizedPlPc(): float
    {
        return $this->unrealizedPlPc;
    }

    /**
     * @return float
     */
    public function getUnrealizedIntradayPl(): float
    {
        return $this->unrealizedIntradayPl;
    }

    /**
     * @return float
     */
    public function getUnrealizedIntradayPlPc(): float
    {
        return $this->unrealizedIntradayPlPc;
    }

    /**
     * @return float
     */
    public function getCurrentPrice(): float
    {
        return $this->currentPrice;
    }

    /**
     * @return float
     */
    public function getLastdayPrice(): float
    {
        return $this->lastdayPrice;
    }

    /**
     * @return float
     */
    public function getChangeToday(): float
    {
        return $this->changeToday;
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
    public function jsonSerialize()
    {
        return $this->raw;
    }
}
