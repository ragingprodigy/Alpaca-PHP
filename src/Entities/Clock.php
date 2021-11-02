<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-28, 07:52.
 * @license Apache-2.0
 */

namespace RagingProdigy\Alpaca\Entities;

use JsonSerializable;

/**
 * Class Clock.
 */
class Clock implements JsonSerializable
{

    /** @var array */
    private array $raw;

    /** @var string */
    private string $timestamp;
    /** @var bool */
    private bool $isOpen;
    /** @var string */
    private string $nextOpen;
    /** @var string */
    private string $nextClose;

    /**
     * Clock constructor.
     * @param array $clock
     */
    public function __construct(array $clock)
    {
        $this->raw = $clock;

        $this->timestamp = $clock['timestamp'];
        $this->isOpen = $clock['is_open'];
        $this->nextOpen = $clock['next_open'];
        $this->nextClose = $clock['next_close'];
    }

    /**
     * @return string
     */
    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    /**
     * @return bool
     */
    public function isOpen(): bool
    {
        return $this->isOpen;
    }

    /**
     * @return string
     */
    public function getNextOpen(): string
    {
        return $this->nextOpen;
    }

    /**
     * @return string
     */
    public function getNextClose(): string
    {
        return $this->nextClose;
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
}
