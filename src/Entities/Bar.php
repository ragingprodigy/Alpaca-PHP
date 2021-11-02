<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-30, 16:07.
 * @license Apache-2.0
 */

namespace RagingProdigy\Alpaca\Entities;

use JsonSerializable;

/**
 * Class Bar.
 */
class Bar implements JsonSerializable
{

    /** @var array array */
    private array $raw;

    /** @var string string */
    private string $symbol;
    /** @var int */
    private int $t;
    /** @var float */
    private float $o;
    /** @var float */
    private float $h;
    /** @var float */
    private float $l;
    /** @var float */
    private float $c;
    /** @var int */
    private int $v;

    /**
     * Bar constructor.
     * @param string $symbol
     * @param array $bar
     */
    public function __construct(string $symbol, array $bar)
    {
        $this->raw = [ $symbol => $bar ];

        $this->symbol = $symbol;
        $this->t = $bar['t'];
        $this->o = $bar['o'];
        $this->h = $bar['h'];
        $this->l = $bar['l'];
        $this->c = $bar['c'];
        $this->v = $bar['v'];
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }

    /**
     * @return int
     */
    public function getT(): int
    {
        return $this->t;
    }

    /**
     * @return float
     */
    public function getO(): float
    {
        return $this->o;
    }

    /**
     * @return float
     */
    public function getH(): float
    {
        return $this->h;
    }

    /**
     * @return float
     */
    public function getL(): float
    {
        return $this->l;
    }

    /**
     * @return float
     */
    public function getC(): float
    {
        return $this->c;
    }

    /**
     * @return int
     */
    public function getV(): int
    {
        return $this->v;
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
