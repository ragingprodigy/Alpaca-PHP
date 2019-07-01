<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-27, 19:26.
 * @license Apache-2.0
 */

namespace RagingProdigy\Alpaca\Traits;

use DateTime;
use RagingProdigy\Alpaca\Constants\OrderAction;
use RagingProdigy\Alpaca\Constants\OrderStatus;
use RagingProdigy\Alpaca\Constants\OrderType;
use RagingProdigy\Alpaca\Constants\Sorting;
use RagingProdigy\Alpaca\Constants\TimeInForce;
use RagingProdigy\Alpaca\Entities\Order;
use RagingProdigy\Alpaca\Exceptions\InvalidApiUsageException;

/**
 * Trait ManagesOrders.
 */
trait ManagesOrders
{

    /**
     * @param string $orderId
     * @return Order
     */
    public function getOrder(string $orderId): Order
    {
        return new Order($this->get("orders/$orderId"));
    }

    /**
     * @param string $clientOrderId
     * @return Order
     */
    public function getOrderByClientOrderId(string $clientOrderId): Order
    {
        return new Order($this->get('orders:by_client_order_id', ['client_order_id' => $clientOrderId]));
    }

    /**
     * @param string $orderId
     */
    public function cancelOrder(string $orderId): void
    {
        $this->delete("orders/$orderId");
    }

    /**
     * @param string $status
     * @param int $limit
     * @param DateTime|null $after
     * @param DateTime|null $until
     * @param string $direction
     * @return Order[]
     */
    public function getOrders(
        string $status = OrderStatus::OPEN,
        int $limit = 50,
        DateTime $after = null,
        DateTime $until = null,
        string $direction = Sorting::DESCENDING
    ): array {
        if (!OrderStatus::isValid($status)) {
            throw new InvalidApiUsageException(sprintf('%s is not a valid Order Status', $status));
        }

        if (!Sorting::isValid($direction)) {
            $direction = Sorting::DESCENDING;
        }

        if ($limit > 500) {
            $limit = 500;
        }

        return array_map(
            static function (array $rawOrder) {
                return new Order($rawOrder);
            },
            $this->get('orders', [
                'status' => $status,
                'limit' => $limit,
                'after' => $after ? $after->format(DATE_ATOM) : null,
                'until' => $until ? $until->format(DATE_ATOM) : null,
                'direction' => $direction,
            ])
        );
    }

    /**
     * @param string $symbol (Symbol or Asset ID)
     * @param int $quantity (Number of Shares to Trade)
     * @param string $action (OrderAction::BUY or OrderAction::SELL)
     * @param string $type (see OrderType)
     * @param string $timeInForce (see TimeInForce)
     * @param float|null $limitPrice
     * @param float|null $stopPrice
     * @param bool $extendedHours
     * @param null $clientOrderId
     * @return Order
     * @see https://docs.alpaca.markets/api-documentation/api-v2/orders/#request-a-new-order
     */
    public function requestNewOrder(
        string $symbol,
        int $quantity,
        string $action,
        string $type,
        string $timeInForce,
        float $limitPrice = null,
        float $stopPrice = null,
        bool $extendedHours = false,
        $clientOrderId = null
    ): Order {
        $this->validateParams($action, $type, $timeInForce, $limitPrice, $stopPrice, $extendedHours, $clientOrderId);

        $payload = [
            'symbol' => $symbol,
            'qty' => (string) $quantity,
            'side' => $action,
            'type' => $type,
            'time_in_force' => $timeInForce,
            'limit_price' => $limitPrice,
            'stop_price' => $stopPrice,
            'extended_hours' => $extendedHours,
            'client_order_id' => $clientOrderId,
        ];

        return new Order($this->post('orders', $payload));
    }

    /**
     * @param string $action
     * @param string $type
     * @param string $timeInForce
     * @param float|null $limitPrice
     * @param float|null $stopPrice
     * @param bool $extendedHours
     * @param string|null $clientOrderId
     */
    private function validateParams(
        string $action,
        string $type,
        string $timeInForce,
        float $limitPrice = null,
        float $stopPrice = null,
        bool $extendedHours = false,
        string $clientOrderId = null
    ): void {
        if ($clientOrderId && strlen($clientOrderId) > 48) {
            throw new InvalidApiUsageException('Client Order ID must be less than 49 characters');
        }

        if (!OrderAction::isValid($action)) {
            throw new InvalidApiUsageException(sprintf('%s is not a valid Order Action', $action));
        }

        if (!OrderType::isValid($type)) {
            throw new InvalidApiUsageException(sprintf('%s is not a valid Order Type', $type));
        }

        if (!TimeInForce::isValid($timeInForce)) {
            throw new InvalidApiUsageException(sprintf('%s is not a valid Time In Force value', $timeInForce));
        }

        if ($extendedHours && ($type !== OrderType::LIMIT || $timeInForce !== TimeInForce::DAY)) {
            throw new InvalidApiUsageException('extendedHours:true Only works with type limit and time_in_force day');
        }

        if (!$stopPrice && (OrderType::STOP === $type || OrderType::STOP_LIMIT === $type)) {
            throw new InvalidApiUsageException('Please specify a Stop Price to use this Order Type');
        }

        if (!$limitPrice && (OrderType::LIMIT === $type || OrderType::STOP_LIMIT === $type)) {
            throw new InvalidApiUsageException('Please specify a Limit Price to use this Order Type');
        }
    }
}
