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

    private array $raw;

    /** @var string */
    private string $assetId;
    /** @var string */
    private string $symbol;
    /** @var string */
    private string $exchange;
    /** @var string */
    private string $assetClass;
    /** @var float */
    private float $averageEntryPrice;
    /** @var int */
    private int $quantity;
    /** @var string */
    private string $side;
    /** @var float */
    private float $marketValue;
    /** @var float */
    private float $costBasis;
    /** @var float */
    private float $unrealizedPl;
    /** @var float */
    private float $unrealizedPlPc;
    /** @var float */
    private float $unrealizedIntradayPl;
    /** @var float */
    private float $unrealizedIntradayPlPc;
    /** @var float */
    private float $currentPrice;
    /** @var float */
    private float $lastdayPrice;
    /** @var float */
    private float $changeToday;

    /**
     * Position constructor.
     * @param array $params
     */
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
    public function jsonSerialize(): array
    {
        return $this->raw;
    }
}
