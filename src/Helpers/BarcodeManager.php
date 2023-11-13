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

use Splash\Core\SplashCore as Splash;
use Splash\OpenApi\Models\Connexion\ConnexionInterface;

/**
 * Tooling Class to Manage Product Multi Ean Barcodes
 */
class BarcodeManager
{
    /**
     * Get Product Barcodes List
     *
     * @param string $productId ProductId
     *
     * @return string[]
     */
    public static function list(ConnexionInterface $connexion, string $productId): array
    {
        try {
            //====================================================================//
            // Get Product Barcodes List
            $listResponse = $connexion->get(
                "/product_barcodes",
                array("product_id" => $productId)
            );
            //====================================================================//
            // Safety Check
            if (!$listResponse) {
                return array();
            }
            /** @var array[] $barcodeItems */
            $barcodeItems = $listResponse['product_barcodes'] ?? array();
            //====================================================================//
            // Parse Barcodes
            $barcodes = array();
            foreach ($barcodeItems as $item) {
                if (is_numeric($item["id"] ?? null) && is_scalar($item["value"] ?? null)) {
                    $barcodes[$item["id"]] = (string) $item["value"];
                }
            }

            return $barcodes;
        } catch (\Throwable | \TypeError) {
            return array();
        }
    }

    /**
     * Create Product Barcode
     */
    public static function add(ConnexionInterface $connexion, string $productId, string $barcode): bool
    {
        //====================================================================//
        // Safety Check
        if (!$productId || !$barcode) {
            return false;
        }
        //====================================================================//
        // Execute Item Create Request
        $createResponse = $connexion->post(
            "/product_barcodes",
            array(
                "product_id" => (int) $productId,
                "key" => "ean",
                "value" => $barcode
            )
        );
        if (!$createResponse) {
            return Splash::log()->err(
                sprintf("Unable to create Barcode %s for %s", $barcode, $productId)
            );
        }

        return true;
    }

    /**
     * Delete Barcode Item
     */
    public static function remove(ConnexionInterface $connexion, string $resourceId): bool
    {
        //====================================================================//
        // Execute Delete Item Request
        if (null === $connexion->delete("/product_barcodes/".$resourceId)) {
            return Splash::log()->err(
                sprintf("Unable to delete Barcode %s", $resourceId)
            );
        }

        return true;
    }
}
