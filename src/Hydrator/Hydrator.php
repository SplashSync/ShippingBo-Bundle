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
     * Extracts data from an object.
     *
     * @param object $object
     *
     * @return array
     */
    public function extract(object $object): array
    {
        return array_filter(parent::extract($object));
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
        return array_filter(parent::extractRequired($object));
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
}
