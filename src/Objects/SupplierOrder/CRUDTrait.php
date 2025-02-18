<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) Splash Sync  <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\Connectors\ShippingBo\Objects\SupplierOrder;

use Splash\OpenApi\Models\Metadata\CRUDTrait as OpenApiCRUDTrait;

/**
 * ShippingBo Orders CRUD Functions
 */
trait CRUDTrait
{
    use OpenApiCRUDTrait{
        OpenApiCRUDTrait::update as coreUpdate;
    }

    /**
     * Add Extra Feature for Supply Capsule Updates
     * - Item Updates
     */
    public function update(bool $needed): ?string
    {
        //====================================================================//
        // Execute Core Action
        $response = $this->coreUpdate($needed);
        if (!$response) {
            return null;
        }
        //====================================================================//
        // Update Order Items
        $this->connector
            ->getLocator()
            ->getSupplyCapsuleItemsManager()
            ->updateItems($this->object)
        ;

        return $response;
    }
}
