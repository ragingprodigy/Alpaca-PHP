<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-28, 07:48.
 * @license Apache-2.0
 */

namespace RagingProdigy\Alpaca\Entities;

use JsonSerializable;

/**
 * Class Calendar.
 */
class Calendar implements JsonSerializable
{

    /** @var array */
    private $raw;

    /** @var string */
    private $date;
    /** @var string */
    private $open;
    /** @var string */
    private $close;

    /**
     * Calendar constructor.
     * @param array $calendar
     */
    public function __construct(array $calendar)
    {
        $this->raw = $calendar;

        $this->date = $calendar['date'];
        $this->open = $calendar['open'];
        $this->close = $calendar['close'];
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getOpen(): string
    {
        return $this->open;
    }

    /**
     * @return string
     */
    public function getClose(): string
    {
        return $this->close;
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
