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
    
    /** @var DateTime */
    private $createdAt;
    /** @var DateTime|null */
    private $updatedAt;
    /** @var DateTime|null */
    private $submittedAt;
    /** @var DateTime|null */
    private $filledAt;
    /** @var DateTime|null */
    private $expiredAt;
    /** @var DateTime|null */
    private $canceledAt;
    /** @var DateTime|null */
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
        $this->createdAt = new DateTime($params['created_at']);
        
        $this->updatedAt = $params['updated_at'] ? new DateTime($params['updated_at']) : null;
        $this->submittedAt = $params['submitted_at'] ? new DateTime($params['submitted_at']) : null;
        $this->filledAt = $params['filled_at'] ? new DateTime($params['filled_at']) : null;
        $this->expiredAt = $params['expired_at'] ? new DateTime($params['expired_at']) : null;
        $this->canceledAt = $params['canceled_at'] ? new DateTime($params['canceled_at']) : null;
        $this->failedAt = $params['failed_at'] ? new DateTime($params['failed_at']) : null;

        $this->assetId = $params['asset_id'];
        $this->assetClass = $params['asset_class'];

        $this->quantity = (int) $params['qty'];
        $this->filledQuantity = (int) $params['filled_qty'];
        $this->type = $params['type'];
        $this->side = $params['side'];
        $this->timeInForce = $params['string'];

        $this->limitPrice = ((double) $params['limit_price']) ?: null;
        $this->stopPrice = ((double) $params['stop_price']) ?: null;
        $this->filledAveragePrice = ((double) $params['filled_average_price']) ?: null;

        $this->status = $params['status'];
        $this->extendedHours = $params['extended_hours'];
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
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @return DateTime|null
     */
    public function getSubmittedAt(): ?DateTime
    {
        return $this->submittedAt;
    }

    /**
     * @return DateTime|null
     */
    public function getFilledAt(): ?DateTime
    {
        return $this->filledAt;
    }

    /**
     * @return DateTime|null
     */
    public function getExpiredAt(): ?DateTime
    {
        return $this->expiredAt;
    }

    /**
     * @return DateTime|null
     */
    public function getCanceledAt(): ?DateTime
    {
        return $this->canceledAt;
    }

    /**
     * @return DateTime|null
     */
    public function getFailedAt(): ?DateTime
    {
        return $this->failedAt;
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
