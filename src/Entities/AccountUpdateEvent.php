<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-07-01, 03:19.
 * @license Apache-2.0
 */

namespace RagingProdigy\Alpaca\Entities;

use JsonSerializable;
use RagingProdigy\Alpaca\Constants\DataStream;

/**
 * Class AccountUpdateEvent.
 */
class AccountUpdateEvent implements UpdateEvent, JsonSerializable
{

    /** @var array */
    private $raw;

    /** @var Account|null */
    private $account;

    public function __construct(array $account)
    {
        $this->raw = $account;
        $this->account = new Account($account);
    }

    /**
     * @return Account|null
     */
    public function getAccount(): ?Account
    {
        return $this->account;
    }

    /**
     * @return string
     */
    public function getMessageType(): string
    {
        return DataStream::ACCOUNT_UPDATES;
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
