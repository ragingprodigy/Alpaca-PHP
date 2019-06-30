<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-30, 17:17.
 * @license Apache-2.0
 */

namespace Tests;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use RagingProdigy\Alpaca\Entities\Bar;
use RagingProdigy\Alpaca\Exceptions\InvalidApiUsageException;
use Tests\Utils\Factory;

/**
 * Class BarsTest.
 */
class BarsTest extends ClientTestCase
{

    public function provideDataForRetrievalValidation(): array
    {
        return [
            'Test With excessive number of Symbols' => [
                'symbols' => (static function () {
                    $symbols = [];
                    for ($i = 0; $i < 300; $i++) {
                        $symbols[] = 'A';
                    }
                    return $symbols;
                })(),
                'timeFrame' => 'invalid.time.frame',
                'message' => 'Maximum number of Symbols exceeded. Only 200 allowed',
            ],
            'Test with Invalid TimeFrame' => [
                'symbols' => ['AA', 'BB'],
                'timeFrame' => 'invalid.time.frame',
                'message' => 'invalid.time.frame is not a valid TimeFrame',
            ],
        ];
    }

    /**
     * @dataProvider provideDataForRetrievalValidation
     * @param array $symbols
     * @param string $timeFrame
     * @param string $message
     */
    public function testBarRetrievalValidations(array $symbols, string $timeFrame, string $message): void
    {
        $this->expectException(InvalidApiUsageException::class);
        $this->expectExceptionMessage($message);

        $this->alpacaClient->getBars($symbols, $timeFrame);
    }
    
    public function testBarsRetrieval(): void
    {
        $bars = Factory::for(Bar::class, 2);
        foreach ($bars as $index => $bar) {
            unset($bars[$index]);
            $bars[array_keys($bar)[0]] = array_values($bar)[0];
        }

        $response = $this->responseForParams($bars);
        $request = new Request('GET', $this->fullDataUrl('bars/day'), $this->requestHeaders());

        $this->httpClient->expects($this->once())->method('send')
            ->with(
                $request,
                [
                    RequestOptions::QUERY => [
                        'symbols' => implode(',', array_keys($bars)),
                        'limit' => 1000,
                        'start' => null,
                        'end' => null,
                    ]
                ]
            )->willReturn($response);

        $barsResponse = $this->alpacaClient->getBars(array_keys($bars), 'day', 3000);

        foreach ($barsResponse as $bar) {
            $this->assertIsString($bar->getSymbol());

            $this->assertIsNumeric($bar->getT());
            $this->assertIsNumeric($bar->getO());
            $this->assertIsNumeric($bar->getH());
            $this->assertIsNumeric($bar->getL());
            $this->assertIsNumeric($bar->getC());
            $this->assertIsNumeric($bar->getV());

            $this->assertIsString('' . $bar);
        }
    }
}
