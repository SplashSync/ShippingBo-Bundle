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

namespace Splash\Connectors\ShippingBo\Helpers;

use Splash\Connectors\ShippingBo\Models\Api\AdditionalReference;
use Splash\Core\SplashCore as Splash;
use Splash\OpenApi\Models\Connexion\ConnexionInterface;

/**
 * Tooling Class to Manage Product Additional References
 */
class AdditionalReferenceManager
{
    /**
     * Create a Product Additional Reference
     *
     * @param string $productId ProductId
     * @param string $reference Reference
     *
     * @return AdditionalReference
     */
    public static function create(string $productId, string $reference): AdditionalReference
    {
        $addRef = new AdditionalReference();
        $addRef->productValue = $productId;
        $addRef->orderItemValue = $reference;

        return $addRef;
    }

    /**
     * Add Product Additional Reference
     */
    public static function add(ConnexionInterface $connexion, AdditionalReference $addRef): bool
    {
        //====================================================================//
        // Safety Check
        if (!$addRef->orderItemValue || !$addRef->productValue) {
            return false;
        }
        //====================================================================//
        // Execute Item Create Request
        $createResponse = $connexion->post(
            "/order_item_product_mappings",
            $addRef->toArray()
        );
        if (!$createResponse) {
            return Splash::log()->err(
                sprintf("Unable to create Additional Ref. %s for %s", $addRef->orderItemValue, $addRef->productValue)
            );
        }

        return true;
    }

    /**
     * Delete Product Additional Reference
     */
    public static function remove(ConnexionInterface $connexion, string $resourceId): bool
    {
        //====================================================================//
        // Execute Delete Item Request
        if (null === $connexion->delete("/order_item_product_mappings/".$resourceId)) {
            return Splash::log()->err(
                sprintf("Unable to delete Additional Ref. %s", $resourceId)
            );
        }

        return true;
    }
}
