<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-30, 05:41.
 * @license Apache-2.0
 */

namespace Tests;

use GuzzleHttp\Psr7\Request;
use RagingProdigy\Alpaca\Entities\Calendar;
use RagingProdigy\Alpaca\Entities\Clock;
use Tests\Utils\Factory;

/**
 * Class ClockAndCalendarTest.
 */
class ClockAndCalendarTest extends ClientTestCase
{
    public function testClockRetrieval(): void
    {
        $clock = Factory::for(Clock::class);
        $response = $this->responseForParams($clock);
        $request = new Request('GET', $this->fullUrl('clock'), $this->requestHeaders());

        $this->httpClient->expects($this->once())->method('send')
            ->with($request, ['query' => []])->willReturn($response);

        $marketClock = $this->alpacaClient->getClock();

        $this->assertEquals($clock, $marketClock->jsonSerialize());
        $this->assertEquals($clock['timestamp'], $marketClock->getTimestamp());
        $this->assertEquals($clock['is_open'], $marketClock->isOpen());
        $this->assertEquals($clock['next_open'], $marketClock->getNextOpen());
        $this->assertEquals($clock['next_close'], $marketClock->getNextClose());

        $this->assertEquals(json_encode($clock), $marketClock);
    }

    public function testCalendarRetrieval(): void
    {
        $marketDates = Factory::for(Calendar::class, 10);
        $response = $this->responseForParams($marketDates);
        $request = new Request('GET', $this->fullUrl('calendar'), $this->requestHeaders());

        $this->httpClient->expects($this->once())->method('send')
            ->with(
                $request,
                ['query' =>
                    ['start' => null, 'end' => null]
                ]
            )
            ->willReturn($response);

        $calendar = $this->alpacaClient->getCalendar();

        $this->assertCount(10, $calendar);

        foreach ($calendar as $day) {
            $this->assertIsString($day->getDate());
            $this->assertIsString($day->getOpen());
            $this->assertIsString($day->getClose());
            $this->assertIsString('' . $day);
        }
    }
}
