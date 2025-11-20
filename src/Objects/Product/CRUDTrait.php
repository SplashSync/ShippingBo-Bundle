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

namespace Splash\Connectors\ShippingBo\Objects\Product;

use Exception;
use Splash\Connectors\ShippingBo\Models\Api\Product;
use Splash\Core\SplashCore as Splash;
use Splash\OpenApi\Models\Objects\CRUDTrait as OpenApiCRUDTrait;

/**
 * ShippingBo Product CRUD Functions
 */
trait CRUDTrait
{
    use OpenApiCRUDTrait{
        OpenApiCRUDTrait::create as coreCreate;
        OpenApiCRUDTrait::update as coreUpdate;
        OpenApiCRUDTrait::delete as coreDelete;
    }

    /**
     * @throws Exception
     *
     * @return null|Product
     */
    public function create(): ?Product
    {
        //====================================================================//
        // Ensure Default Source
        $this->in['source'] = $this->in['source'] ?? "Splashsync";
        //====================================================================//
        // Ensure Empty Stock on Create
        $this->in['stock'] ??= 0;
        //====================================================================//
        // Check if Product Create is Allowed for Supplier
        $supplierName = $this->in['supplier'] ?? null;
        if (is_string($supplierName) && $this->isFilteredBySupplier($supplierName)) {
            //====================================================================//
            // Skip the Create and return an Error
            return $this->logFilteredBySupplier();
        }
        //====================================================================//
        // Execute Core Action
        $product = $this->coreCreate();

        return ($product instanceof Product) ? $product : null;
    }

    /**
     * @inheritDoc
     */
    public function update(bool $needed): ?string
    {
        //====================================================================//
        // Check if Product Update is Allowed for Supplier
        if ($this->isFilteredBySupplier($this->object->supplier)) {
            //====================================================================//
            // Skip the Update and return Object Identifier
            return $this->getObjectIdentifier();
        }

        //====================================================================//
        // Execute Core Action
        return $this->coreUpdate($needed);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $objectId = null): bool
    {
        //====================================================================//
        // Stack Trace
        Splash::log()->trace();
        if (empty($objectId)) {
            return true;
        }
        //====================================================================//
        // Load Remote Object
        $object = $this->load($objectId);
        if (empty($object)) {
            return Splash::log()->warTrace("Trying to Delete an Unknown Object (".$objectId.").");
        }
        //====================================================================//
        // Check if Product Delete is Allowed for Supplier
        if ($this->isFilteredBySupplier($object->supplier)) {
            //====================================================================//
            // Skip the Delete and return an Error
            return (bool) $this->logFilteredBySupplier();
        }
        //====================================================================//
        // Delete Remote Object
        $deleteResponse = $this->visitor->delete($object);

        return $deleteResponse->isSuccess();
    }
}
