<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-30, 06:43.
 * @license Apache-2.0
 */

namespace Tests;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use RagingProdigy\Alpaca\Constants\OrderAction;
use RagingProdigy\Alpaca\Constants\OrderStatus;
use RagingProdigy\Alpaca\Constants\OrderType;
use RagingProdigy\Alpaca\Constants\TimeInForce;
use RagingProdigy\Alpaca\Entities\Order;
use RagingProdigy\Alpaca\Exceptions\InvalidApiUsageException;
use Tests\Utils\Factory;

/**
 * Class OrdersTest.
 */
class OrdersTest extends ClientTestCase
{
    public function testOrdersRetrieval(): void
    {
        $rawOrders = Factory::for(Order::class, 10);
        $result = $this->responseForParams($rawOrders);
        $this->httpClient->expects($this->once())->method('send')
            ->with(
                new Request(
                    'GET',
                    $this->fullUrl('orders'),
                    $this->requestHeaders()
                ),
                [
                    RequestOptions::QUERY => [
                        'status' => 'open',
                        'limit' => 50,
                        'after' => null,
                        'until' => null,
                        'direction' => 'desc',
                    ],
                ]
            )->willReturn($result);

        $orders = $this->alpacaClient->getOrders();
        $this->assertCount(10, $orders);

        foreach ($orders as $order) {
            $this->assertOrderIsValid($order);
        }
    }

    public function testOrdersRetrievalWithInvalidStatus(): void
    {
        $this->expectException(InvalidApiUsageException::class);
        $this->expectExceptionMessage('invalid.status is not a valid Order Status');
        $this->alpacaClient->getOrders('invalid.status');
    }

    public function testOrderRetrievalWithParamsCheck(): void
    {
        // Sorting and Limit
        $rawOrders = Factory::for(Order::class, 10);
        $result = $this->responseForParams($rawOrders);
        $this->httpClient->expects($this->once())->method('send')
            ->with(
                new Request(
                    'GET',
                    $this->fullUrl('orders'),
                    $this->requestHeaders()
                ),
                [
                    RequestOptions::QUERY => [
                        'status' => 'all',
                        'limit' => 500,
                        'after' => null,
                        'until' => null,
                        'direction' => 'desc',
                    ],
                ]
            )->willReturn($result);

        $orders = $this->alpacaClient->getOrders(OrderStatus::ALL, 600, null, null, 'invalid.sort.direction');
        $this->assertCount(10, $orders);
    }

    public function testSingleOrderRetrieval(): void
    {
        $rawOrder = Factory::for(Order::class);
        $result = $this->responseForParams($rawOrder);
        $this->httpClient->expects($this->once())->method('send')
            ->with(
                new Request(
                    'GET',
                    $this->fullUrl('orders/' . $rawOrder['id']),
                    $this->requestHeaders()
                ),
                [ RequestOptions::QUERY => [] ]
            )->willReturn($result);

        $order = $this->alpacaClient->getOrder($rawOrder['id']);

        $this->assertOrderIsValid($order);
        $this->assertOrderObjectIsSameValueAsRaw($rawOrder, $order);
    }

    public function testOrderRetrievalByClientOrderId(): void
    {
        $rawOrder = Factory::for(Order::class);
        $result = $this->responseForParams($rawOrder);
        $this->httpClient->expects($this->once())->method('send')
            ->with(
                new Request(
                    'GET',
                    $this->fullUrl('orders:by_client_order_id'),
                    $this->requestHeaders()
                ),
                [ RequestOptions::QUERY => [
                    'client_order_id' => $rawOrder['client_order_id']
                ] ]
            )->willReturn($result);

        $order = $this->alpacaClient->getOrderByClientOrderId($rawOrder['client_order_id']);

        $this->assertOrderIsValid($order);
        $this->assertOrderObjectIsSameValueAsRaw($rawOrder, $order);
    }

    public function testOrderCancellation(): void
    {
        $result = $this->responseForParams(null, 201);
        $result->expects($this->never())->method('getBody');

        $this->httpClient->expects($this->once())->method('send')
            ->with(
                new Request(
                    'DELETE',
                    $this->fullUrl('orders/order.id'),
                    $this->requestHeaders()
                ),
                [ RequestOptions::QUERY => [ ] ]
            )->willReturn($result);

        $this->alpacaClient->cancelOrder('order.id');
    }

    public function testOrderCreation(): void
    {
        $orderParam = [
            'symbol' => 'symbol',
            'qty' => '3',
            'side' =>'buy',
            'type' => OrderType::MARKET,
            'time_in_force' => TimeInForce::DAY,
            'limit_price' => 100,
            'stop_price' => 105,
            'extended_hours' => false,
            'client_order_id' => 'some.uuid',
        ];

        $orderResponse = array_merge($orderParam, [
            'id' => 'id',
            'asset_id' => 'asset.id',
            'asset_class' => 'asset.class',
            'created_at' => 'date',
            'filled_qty' => '3',
            'status' => 'active',
            'updated_at' => null,
            'submitted_at' => null,
            'filled_at' => null,
            'expired_at' => null,
            'canceled_at' => null,
            'failed_at' => null,
        ]);

        $result = $this->responseForParams($orderResponse);
        $this->httpClient->expects($this->once())->method('send')->willReturn($result);

        $order = $this->alpacaClient->requestNewOrder(
            'symbol',
            3,
            OrderAction::BUY,
            OrderType::MARKET,
            TimeInForce::DAY,
            100.0,
            105.0,
            false,
            'some.uuid'
        );

        $this->assertEquals($orderResponse, $order->jsonSerialize());
    }

    /**
     * @return array
     */
    public function provideDataForFailedOrderCreationTest(): array
    {
        return [
            'Test Client Order ID Longer than 48 characters' => [
                'payload' => [
                    'clientOrderId' => 'hkdshfksdhfkjshdfjkhsdkfjhsdfkskjsdbkjfsdkfdsjhfsdf',
                    'symbol' => 'ds',
                    'quantity' => 2,
                    'action' => 'sell',
                    'type' => 'market',
                    'timeInForce' => 'day'
                ],
                'message' => 'Client Order ID must be less than 49 characters'
            ],
            'Test Order Creation with Invalid Action' => [
                'payload' => [
                    'symbol' => 'ds',
                    'quantity' => 2,
                    'action' => 'some.action',
                    'type' => 'market',
                    'timeInForce' => 'day'
                ],
                'message' => 'some.action is not a valid Order Action',
            ],
            'Test Order Creation with Invalid Type' => [
                'payload' => [
                    'symbol' => 'ds',
                    'quantity' => 2,
                    'action' => 'sell',
                    'type' => 'random',
                    'timeInForce' => 'day'
                ],
                'message' => 'random is not a valid Order Type',
            ],
            'Test Order Creation with Time In Force' => [
                'payload' => [
                    'symbol' => 'ds',
                    'quantity' => 2,
                    'action' => 'sell',
                    'type' => 'market',
                    'timeInForce' => 'sss'
                ],
                'message' => 'sss is not a valid Time In Force value',
            ],
            'Test setting extended to True for the wrong order type' => [
                'payload' => [
                    'symbol' => 'ds',
                    'quantity' => 2,
                    'action' => 'sell',
                    'type' => 'market',
                    'timeInForce' => 'fok',
                    'extendedHours' => true,
                ],
                'message' => 'extendedHours:true Only works with type limit and time_in_force day',
            ],
            'Require Stop Price for Order Type: STOP' => [
                'payload' => [
                    'symbol' => 'ds',
                    'quantity' => 2,
                    'action' => 'sell',
                    'type' => 'stop',
                    'timeInForce' => 'day',
                ],
                'message' => 'Please specify a Stop Price to use this Order Type',
            ],
            'Require Stop Price for Order Type: STOP_LIMIT' => [
                'payload' => [
                    'symbol' => 'ds',
                    'quantity' => 2,
                    'action' => 'sell',
                    'type' => 'stop_limit',
                    'timeInForce' => 'day',
                ],
                'message' => 'Please specify a Stop Price to use this Order Type',
            ],
            'Require Limit Price for Order Type: LIMIT' => [
                'payload' => [
                    'symbol' => 'ds',
                    'quantity' => 2,
                    'action' => 'sell',
                    'type' => 'limit',
                    'timeInForce' => 'day',
                ],
                'message' => 'Please specify a Limit Price to use this Order Type',
            ],
            'Require Limit Price for Order Type: STOP_LIMIT' => [
                'payload' => [
                    'symbol' => 'ds',
                    'quantity' => 2,
                    'action' => 'sell',
                    'type' => 'stop_limit',
                    'timeInForce' => 'day',
                    'stopPrice' => 100.0,
                ],
                'message' => 'Please specify a Limit Price to use this Order Type',
            ],
        ];
    }

    /**
     * @dataProvider provideDataForFailedOrderCreationTest
     * @param array $payload
     * @param string $message
     */
    public function testFailedOrderCreation(array $payload, string $message): void
    {
        $this->expectException(InvalidApiUsageException::class);
        $this->expectExceptionMessage($message);

        $this->alpacaClient->requestNewOrder(
            $payload['symbol'],
            $payload['quantity'],
            $payload['action'],
            $payload['type'],
            $payload['timeInForce'],
            $payload['limitPrice'] ?? null,
            $payload['stopPrice'] ?? null,
            $payload['extendedHours'] ?? false,
            $payload['clientOrderId'] ?? null
        );
    }

    /**
     * @param Order $order
     */
    private function assertOrderIsValid(Order $order): void
    {
        $this->assertIsString('' . $order);

        $this->assertIsString($order->getSide());
        $this->assertIsString($order->getSymbol());
        $this->assertIsString($order->getAssetClass());
        $this->assertIsString($order->getAssetId());
        $this->assertIsString($order->getStatus());
        $this->assertIsString($order->getId());
        $this->assertIsString($order->getClientOrderId());
        $this->assertIsString($order->getType());
        $this->assertIsString($order->getTimeInForce());

        $this->assertIsString($order->getCreatedAt());
        $this->assertIsString($order->getUpdatedAt());
        $this->assertIsString($order->getSubmittedAt());
        $this->assertIsString($order->getCanceledAt());
        $this->assertIsString($order->getExpiredAt());
        $this->assertIsString($order->getFilledAt());
        $this->assertIsString($order->getFailedAt());

        $this->assertIsNumeric($order->getQuantity());
        $this->assertIsNumeric($order->getFilledQuantity());
        $this->assertIsNumeric($order->getFilledAveragePrice());
        $this->assertIsNumeric($order->getLimitPrice());
        $this->assertIsNumeric($order->getStopPrice());

        $this->assertIsBool($order->isExtendedHours());
    }

    /**
     * @param array $rawOrder
     * @param Order $order
     */
    private function assertOrderObjectIsSameValueAsRaw(array $rawOrder, Order $order): void
    {
        $this->assertEquals($rawOrder['id'], $order->getId());
        $this->assertEquals($rawOrder['side'], $order->getSide());
        $this->assertEquals($rawOrder['symbol'], $order->getSymbol());
        $this->assertEquals($rawOrder['asset_class'], $order->getAssetClass());
        $this->assertEquals($rawOrder['asset_id'], $order->getAssetId());
        $this->assertEquals($rawOrder['status'], $order->getStatus());
        $this->assertEquals($rawOrder['client_order_id'], $order->getClientOrderId());
        $this->assertEquals($rawOrder['type'], $order->getType());
        $this->assertEquals($rawOrder['time_in_force'], $order->getTimeInForce());

        $this->assertEquals($rawOrder['created_at'], $order->getCreatedAt());
        $this->assertEquals($rawOrder['updated_at'], $order->getUpdatedAt());
        $this->assertEquals($rawOrder['submitted_at'], $order->getSubmittedAt());
        $this->assertEquals($rawOrder['canceled_at'], $order->getCanceledAt());
        $this->assertEquals($rawOrder['expired_at'], $order->getExpiredAt());
        $this->assertEquals($rawOrder['filled_at'], $order->getFilledAt());
        $this->assertEquals($rawOrder['failed_at'], $order->getFailedAt());

        $this->assertEquals($rawOrder['qty'], $order->getQuantity());
        $this->assertEquals($rawOrder['filled_qty'], $order->getFilledQuantity());
        $this->assertEquals($rawOrder['filled_average_price'], $order->getFilledAveragePrice());
        $this->assertEquals($rawOrder['limit_price'], $order->getLimitPrice());
        $this->assertEquals($rawOrder['stop_price'], $order->getStopPrice());

        $this->assertEquals($rawOrder['extended_hours'], $order->isExtendedHours());
    }
}
