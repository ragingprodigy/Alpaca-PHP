<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-07-01, 00:27.
 * @license Apache-2.0
 */

namespace RagingProdigy\Alpaca\Traits;

use RagingProdigy\Alpaca\Constants\DataStream;
use RagingProdigy\Alpaca\Entities\AccountUpdateEvent;
use RagingProdigy\Alpaca\Entities\OrderUpdateEvent;
use RagingProdigy\Alpaca\Exceptions\AlpacaAPIException;
use RagingProdigy\Alpaca\Exceptions\InvalidApiUsageException;
use Ratchet\Client\WebSocket;
use Ratchet\RFC6455\Messaging\MessageInterface;
use React\Promise\PromiseInterface;
use function Ratchet\Client\connect;

/**
 * Trait StreamsUpdates.
 */
trait StreamsUpdates
{
    /**
     * @param array $streams
     * @param callable $onSuccess
     * @param callable $onFailure
     * @return PromiseInterface
     */
    public function connectToStreams(array $streams, callable $onSuccess, callable $onFailure = null): PromiseInterface
    {
        foreach ($streams as $stream) {
            if (!DataStream::isValid($stream)) {
                throw new InvalidApiUsageException(sprintf('%s is not a valid data stream.', $stream));
            }
        }

        $streamUrl = str_replace(['http', 'v2'], ['ws', 'stream'], $this->getBaseUrl());
        return connect($streamUrl)->then($this->initiateStreams($streams, $onSuccess), $onFailure);
    }

    /**
     * @param array $streams
     * @param callable $callback
     * @return callable
     */
    private function initiateStreams(array $streams, callable $callback): callable
    {
        return function (WebSocket $connection) use ($streams, $callback) {
            $this->authenticateStream($connection)->subscribe($connection, $streams)->listen($connection, $callback);
        };
    }

    /**
     * @param WebSocket $connection
     * @return StreamsUpdates
     */
    private function authenticateStream(WebSocket $connection): self
    {
        $payload = [
            'action' => 'authenticate',
            'data' => [
                'key_id' => $this->getApiKey(),
                'secret_key' => $this->getApiSecret(),
            ],
        ];

        $this->listen($connection, static function (WebSocket $connection, $message) {
            if (is_array($message) && ('authorization' === $message['stream'])
                && 'authenticate' === $message['data']['action'] && 'unauthorized' === $message['data']['status']) {
                $connection->close();
                throw new AlpacaAPIException('Authentication Failed');
            }
        });

        $connection->send(json_encode($payload));

        return $this;
    }

    /**
     * @param WebSocket $connection
     * @param array $streams
     * @return StreamsUpdates
     */
    private function subscribe(WebSocket $connection, array $streams): self
    {
        $connection->send(json_encode([
            'action' => 'listen',
            'data' => [
                'streams' => $streams,
            ],
        ]));

        return $this;
    }

    /**
     * @param WebSocket $connection
     * @param callable $callback
     * @param bool $oneTime
     * @return StreamsUpdates
     */
    private function listen(WebSocket $connection, callable $callback, $oneTime = false): self
    {
        $connection->{$oneTime ? 'once' : 'on'}('message', static function (MessageInterface $message) use ($connection, $callback) {
            if (($data = json_decode($message->__toString(), true)) === null) {
                throw new AlpacaAPIException('Unexpected Data Received');
            }

            // Extract Relevant Data
            if (DataStream::ACCOUNT_UPDATES === $data['stream']) {
                $dataMessage = new AccountUpdateEvent($data['data']);
            } elseif (DataStream::TRADE_UPDATES === $data['stream']) {
                $dataMessage = new OrderUpdateEvent($data['data']);
            } else {
                $dataMessage = null;
            }

            return $callback($connection, $dataMessage);
        });

        return $this;
    }
}
