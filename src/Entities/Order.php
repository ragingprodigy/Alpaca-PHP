<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-27, 19:10.
 * @license Apache-2.0
 */

namespace RagingProdigy\Alpaca\Entities;

use DateTime;
use Exception;
use JsonSerializable;

/**
 * Class Order.
 */
class Order implements JsonSerializable
{
    private $raw;
    
    /** @var string */
    private $id;
    /** @var string */
    private $clientOrderId;
    
    /** @var string|null */
    private $createdAt;
    /** @var string|null */
    private $updatedAt;
    /** @var string|null */
    private $submittedAt;
    /** @var string|null */
    private $filledAt;
    /** @var string|null */
    private $expiredAt;
    /** @var string|null */
    private $canceledAt;
    /** @var string|null */
    private $failedAt;
    
    /** @var string */
    private $assetId;
    /** @var string */
    private $symbol;
    /** @var string */
    private $assetClass;
    /** @var int */
    private $quantity;
    /** @var int */
    private $filledQuantity;
    /** @var string */
    private $type;
    /** @var string */
    private $side;
    /** @var string */
    private $timeInForce;
    /** @var double|null */
    private $limitPrice;
    /** @var double|null */
    private $stopPrice;

    /** @var double|null */
    private $filledAveragePrice;

    /** @var string */
    private $status;
    /** @var bool */
    private $extendedHours;

    /**
     * Order constructor.
     * @param array $params
     * @throws Exception
     */
    public function __construct(array $params)
    {
        $this->raw = $params;
        
        $this->id = $params['id'];
        $this->clientOrderId = $params['client_order_id'];
        $this->createdAt = $params['created_at'];
        
        $this->updatedAt = $params['updated_at'];
        $this->submittedAt = $params['submitted_at'];
        $this->filledAt = $params['filled_at'];
        $this->expiredAt = $params['expired_at'];
        $this->canceledAt = $params['canceled_at'];
        $this->failedAt = $params['failed_at'];

        $this->assetId = $params['asset_id'];
        $this->assetClass = $params['asset_class'];

        $this->quantity = (int) $params['qty'];
        $this->filledQuantity = (int) $params['filled_qty'];
        $this->type = $params['type'];
        $this->side = $params['side'];
        $this->timeInForce = $params['time_in_force'];

        $this->limitPrice = ((double) $params['limit_price']) ?: null;
        $this->stopPrice = ((double) $params['stop_price']) ?: null;

        if (isset($params['filled_average_price'])) {
            $this->filledAveragePrice = (double) $params['filled_average_price'];
        }

        $this->status = $params['status'];
        $this->extendedHours = $params['extended_hours'] ?? false;
    }

    /**
     * @return array
     */
    public function getRaw(): array
    {
        return $this->raw;
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
    public function getClientOrderId(): string
    {
        return $this->clientOrderId;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
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
    public function getAssetClass(): string
    {
        return $this->assetClass;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    /**
     * @return string|null
     */
    public function getSubmittedAt(): ?string
    {
        return $this->submittedAt;
    }

    /**
     * @return string|null
     */
    public function getFilledAt(): ?string
    {
        return $this->filledAt;
    }

    /**
     * @return string|null
     */
    public function getExpiredAt(): ?string
    {
        return $this->expiredAt;
    }

    /**
     * @return string|null
     */
    public function getCanceledAt(): ?string
    {
        return $this->canceledAt;
    }

    /**
     * @return string|null
     */
    public function getFailedAt(): ?string
    {
        return $this->failedAt;
    }

    /**
     * @return int
     */
    public function getFilledQuantity(): int
    {
        return $this->filledQuantity;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getSide(): string
    {
        return $this->side;
    }

    /**
     * @return string
     */
    public function getTimeInForce(): string
    {
        return $this->timeInForce;
    }

    /**
     * @return float|null
     */
    public function getLimitPrice(): ?float
    {
        return $this->limitPrice;
    }

    /**
     * @return float|null
     */
    public function getStopPrice(): ?float
    {
        return $this->stopPrice;
    }

    /**
     * @return float|null
     */
    public function getFilledAveragePrice(): ?float
    {
        return $this->filledAveragePrice;
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
    public function isExtendedHours(): bool
    {
        return $this->extendedHours;
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
