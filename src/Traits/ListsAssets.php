<?php
declare(strict_types=1);

/**
 * Created by: Oladapo Omonayajo <o.omonayajo@gmail.com>
 * Created on: 2019-06-28, 08:03.
 * @license Apache-2.0
 */

namespace RagingProdigy\Alpaca\Traits;

use RagingProdigy\Alpaca\Constants\AssetStatus;
use RagingProdigy\Alpaca\Entities\Asset;
use RagingProdigy\Alpaca\Exceptions\InvalidApiUsageException;

/**
 * Trait ListsAssets.
 */
trait ListsAssets
{
    /**
     * @param string $status
     * @return array|Asset[]
     */
    public function getAssets(string $status = ''): array
    {
        if ($status && !AssetStatus::isValid($status)) {
            throw new InvalidApiUsageException('Please provide a valid asset status');
        }

        return array_map(static function (array $asset) {
            return new Asset($asset);
        }, $this->get('assets', ['status' => $status]));
    }

    /**
     * @param string $assetIdOrSymbol
     * @return Asset
     */
    public function getAsset(string $assetIdOrSymbol): Asset
    {
        return new Asset($this->get("assets/$assetIdOrSymbol"));
    }
}
