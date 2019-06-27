<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-27, 18:17.
 * @license Apache-2.0
 */

namespace RagingProdigy\Alpaca\Entities;

use DateTime;
use Exception;
use JsonSerializable;

/**
 * Class Account.
 */
class Account implements JsonSerializable
{
    /** @var array */
    private $raw;

    /** @var string */
    private $id;
    /** @var string */
    private $status;
    /** @var string */
    private $currency;
    /** @var double */
    private $buyingPower;
    /** @var double */
    private $cash;
    /** @var double */
    private $portfolioValue;
    /** @var bool */
    private $patternDayTrader;
    /** @var bool */
    private $tradeSuspendedByUser;
    /** @var bool */
    private $tradingBlocked;
    /** @var bool */
    private $transfersBlocked;
    /** @var bool */
    private $accountBlocked;
    /** @var DateTime */
    private $createdAt;
    /** @var bool */
    private $shortingEnabled;
    /** @var int */
    private $multiplier;
    /** @var double */
    private $longMarketValue;
    /** @var double */
    private $shortMarketValue;
    /** @var $double */
    private $equity;
    /** @var double */
    private $lastEquity;
    /** @var double */
    private $initialMargin;
    /** @var double */
    private $maintenanceMargin;
    /** @var int */
    private $daytradeCount;
    /** @var double */
    private $sma;

    /**
     * Account constructor.
     * @param array $params
     * @throws Exception
     */
    public function __construct(array $params)
    {
        $this->raw = $params;
        $this->id = $params['id'];
        $this->status = $params['status'];
        $this->currency = $params['currency'];
        $this->buyingPower = (double) $params['buying_power'];
        $this->cash = (double) $params['cash'];
        $this->portfolioValue = (double) $params['portfolio_value'];
        $this->patternDayTrader = $params['pattern_day_trader'];
        $this->tradeSuspendedByUser = $params['trade_suspended_by_user'];
        $this->tradingBlocked = $params['trading_blocked'];
        $this->transfersBlocked = $params['transfers_blocked'];
        $this->accountBlocked = $params['account_blocked'];
        $this->createdAt = new DateTime($params['created_at']);
        $this->shortingEnabled = $params['shorting_enabled'];
        $this->longMarketValue = (double) $params['long_market_value'];
        $this->shortMarketValue = (double) $params['short_market_value'];
        $this->equity = (double) $params['equity'];
        $this->lastEquity = (double) $params['last_equity'];
        $this->multiplier = (int) $params['multiplier'];
        $this->initialMargin = (double) $params['initial_margin'];
        $this->maintenanceMargin = (double) $params['maintenance_margin'];
        $this->daytradeCount = (int) $params['daytrade_count'];
        $this->sma = (double) $params['sma'];
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
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @return float
     */
    public function getBuyingPower(): float
    {
        return $this->buyingPower;
    }

    /**
     * @return float
     */
    public function getCash(): float
    {
        return $this->cash;
    }

    /**
     * @return float
     */
    public function getPortfolioValue(): float
    {
        return $this->portfolioValue;
    }

    /**
     * @return bool
     */
    public function isPatternDayTrader(): bool
    {
        return $this->patternDayTrader;
    }

    /**
     * @return bool
     */
    public function isTradeSuspendedByUser(): bool
    {
        return $this->tradeSuspendedByUser;
    }

    /**
     * @return bool
     */
    public function isTradingBlocked(): bool
    {
        return $this->tradingBlocked;
    }

    /**
     * @return bool
     */
    public function isTransfersBlocked(): bool
    {
        return $this->transfersBlocked;
    }

    /**
     * @return bool
     */
    public function isAccountBlocked(): bool
    {
        return $this->accountBlocked;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return bool
     */
    public function isShortingEnabled(): bool
    {
        return $this->shortingEnabled;
    }

    /**
     * @return int
     */
    public function getMultiplier(): int
    {
        return $this->multiplier;
    }

    /**
     * @return float
     */
    public function getLongMarketValue(): float
    {
        return $this->longMarketValue;
    }

    /**
     * @return float
     */
    public function getShortMarketValue(): float
    {
        return $this->shortMarketValue;
    }

    /**
     * @return mixed
     */
    public function getEquity()
    {
        return $this->equity;
    }

    /**
     * @return float
     */
    public function getLastEquity(): float
    {
        return $this->lastEquity;
    }

    /**
     * @return float
     */
    public function getInitialMargin(): float
    {
        return $this->initialMargin;
    }

    /**
     * @return float
     */
    public function getMaintenanceMargin(): float
    {
        return $this->maintenanceMargin;
    }

    /**
     * @return int
     */
    public function getDaytradeCount(): int
    {
        return $this->daytradeCount;
    }

    /**
     * @return float
     */
    public function getSma(): float
    {
        return $this->sma;
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
