<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-07-01, 02:54.
 * @license Apache-2.0
 */

namespace RagingProdigy\Alpaca\Entities;

use JsonSerializable;
use RagingProdigy\Alpaca\Constants\DataStream;

/**
 * Class OrderUpdateEvent.
 */
class OrderUpdateEvent implements JsonSerializable, UpdateEvent
{

    /** @var array */
    private array $raw;

    /** @var string|null */
    private string|null $event;

    /** @var float|null */
    private float|null $price;

    /** @var string|null */
    private string|null $timestamp;

    /** @var Order */
    private Order $order;

    public function __construct(array $data)
    {
        $this->raw = $data;

        if (isset($data['order'])) {
            $this->order = new Order($data['order']);
        }

        if (isset($data['price'])) {
            $this->price = (double) $data['price'];
        }

        $this->event = $data['event'] ?? null;
        $this->timestamp = $data['timestamp'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getEvent(): ?string
    {
        return $this->event;
    }

    /**
     * @return float|null
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @return string|null
     */
    public function getTimestamp(): ?string
    {
        return $this->timestamp;
    }

    /**
     * @return Order
     */
    public function getOrder(): Order
    {
        return $this->order;
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

    /**
     * @return string
     */
    public function getMessageType(): string
    {
        return DataStream::TRADE_UPDATES;
    }
}
