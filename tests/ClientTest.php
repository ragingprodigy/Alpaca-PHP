<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-30, 20:06.
 * @license Apache-2.0
 */

namespace Tests;

use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\RequestInterface;
use RagingProdigy\Alpaca\Exceptions\AlpacaAPIException;
use Throwable;

/**
 * Class ClientTest.
 */
class ClientTest extends ClientTestCase
{
    public function provideDataForExceptionTest(): array
    {
        $requestMock = $this->createMock(RequestInterface::class);

        return [
            'Test Client Exception' => [
                'exceptionClassName' => ClientException::class,
                'arguments' => ['message', $requestMock],
            ],
            'Test Connect Exception' => [
                'exceptionClassName' => ConnectException::class,
                'arguments' => ['message', $requestMock],
            ],
            'Test Server Exception' => [
                'exceptionClassName' => ServerException::class,
                'arguments' => ['message', $requestMock],
            ],
            'Test BadResponse Exception' => [
                'exceptionClassName' => BadResponseException::class,
                'arguments' => ['message', $requestMock],
            ],
        ];
    }

    /**
     * @dataProvider provideDataForExceptionTest
     * @param string $exceptionClassName
     * @param array $arguments
     */
    public function testClientExceptions(string $exceptionClassName, array $arguments): void
    {
        /** @var MockObject|Throwable $exception */
        $exception = $this->getMockBuilder($exceptionClassName)
            ->setConstructorArgs($arguments)
            ->getMock();

        $this->httpClient->expects($this->once())->method('send')
            ->willThrowException($exception);

        $this->expectException(AlpacaAPIException::class);

        $this->alpacaClient->getClock();
    }
}
