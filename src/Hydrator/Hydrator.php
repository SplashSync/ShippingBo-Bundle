<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) 2015-2021 Splash Sync  <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\Connectors\ShippingBo\Hydrator;

use Splash\OpenApi\Hydrator\Hydrator as BaseHydrator;

/**
 * ShippingBo API Objects Hydrator
 */
class Hydrator extends BaseHydrator
{
    /**
     * Additional Data for Extractor
     *
     * @var array
     */
    private array $extractExtras = array();

    /**
     * Extracts data from an object.
     *
     * @param object $object
     *
     * @return array
     */
    public function extract(object $object): array
    {
        $data = self::filter(array_merge(
            parent::extract($object),
            $this->extractExtras
        ));
        $this->extractExtras = array();

        return $data;
    }

    /**
     * Extracts required data from an object.
     *
     * @param object $object
     *
     * @return array
     */
    public function extractRequired(object $object): array
    {
        $data = self::filter(array_merge(
            parent::extractRequired($object),
            $this->extractExtras
        ));
        $this->extractExtras = array();

        return $data;
    }

    /**
     * Hydrate object from array.
     *
     * @param array  $data
     * @param string $type
     *
     * @return object
     */
    public function hydrate(array $data, string $type): object
    {
        return parent::hydrate(
            $data[constant($type."::ITEMS_PROP")] ?? $data,
            $type
        );
    }

    /**
     * Hydrate many object from array.
     *
     * @param array  $data
     * @param string $type
     *
     * @return object[]
     */
    public function hydrateMany(array $data, string $type): array
    {
        return parent::hydrateMany(
            $data[constant($type."::COLLECTION_PROP")] ?? array(),
            $type
        );
    }

    /**
     * Add Data to Next Extract from an object.
     *
     * @param array $extraData
     *
     * @return void
     */
    public function setExtractExtra(array $extraData): void
    {
        $this->extractExtras = $extraData;
    }

    /**
     * Filter Output Data to Remove Nul Values
     */
    private static function filter(array $data): array
    {
        return array_filter($data, function ($value) {
            if (is_null($value)) {
                return false;
            }
            if (is_array($value) && empty($value)) {
                return false;
            }

            return true;
        });
    }
}
