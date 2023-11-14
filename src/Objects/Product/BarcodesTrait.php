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
use Splash\Client\Splash;
use Splash\Connectors\ShippingBo\Helpers\BarcodeManager;
use Splash\Models\Helpers\InlineHelper;

/**
 * Manage Product Barcodes Fields
 */
trait BarcodesTrait
{
    /**
     * Build Status Fields
     *
     * @return void
     */
    protected function buildBarcodesFields(): void
    {
        //====================================================================//
        // Product Multi-Ean Barcodes
        $this->fieldsFactory()->create(SPL_T_INLINE)
            ->identifier("ean13_multi")
            ->name("Multi-Ean")
        ;
        //====================================================================//
        // Product Multi-Ean Master Barcodes
        $this->fieldsFactory()->create(SPL_T_INT)
            ->identifier("ean13_multi_master")
            ->name("Multi-Ean Master")
            ->isWriteOnly()
        ;
    }

    /**
     * Read requested Field
     */
    protected function getBarcodesFields(string $key, string $fieldName): void
    {
        //====================================================================//
        // READ Fields
        switch ($fieldName) {
            case 'ean13_multi':
                $this->out[$fieldName] = InlineHelper::fromArray(BarcodeManager::list(
                    $this->getVisitor()->getConnexion(),
                    $this->object->id
                ));

                break;
            default:
                return;
        }

        unset($this->in[$key]);
    }

    /**
     * Write Given Fields
     *
     * @param string $fieldName Field Identifier / Name
     * @param string $fieldData Field Data
     *
     * @throws Exception
     */
    protected function setBarcodesFields(string $fieldName, string $fieldData): void
    {
        //====================================================================//
        // WRITE Field
        switch ($fieldName) {
            case 'ean13_multi':
                if (!$this->updateBarcodes(InlineHelper::toArray($fieldData))) {
                    Splash::log()->err("An error occurred while updating Product Barcodes");
                }

                break;
            case 'ean13_multi_master':
                //====================================================================//
                // Load Current Ean's
                $barcodes = BarcodeManager::list(
                    $this->getVisitor()->getConnexion(),
                    $this->object->id
                );
                //====================================================================//
                // Ensure Master Ean is Present
                $barcode = trim($fieldData);
                if (empty($barcode) || in_array($barcode, $barcodes, false)) {
                    break;
                }
                //====================================================================//
                // Add Ean to Product
                BarcodeManager::add(
                    $this->getVisitor()->getConnexion(),
                    $this->object->id,
                    $barcode,
                );

                break;
        }
        unset($this->in[$fieldName]);
    }

    /**
     * Update Product Barcodes
     *
     * @param string[] $new
     *
     * @throws Exception
     *
     * @return bool
     */
    protected function updateBarcodes(array $new): bool
    {
        $expected = $success = 0;
        $connexion = $this->getVisitor()->getConnexion();
        //====================================================================//
        // Get Current Barcodes
        $current = BarcodeManager::list($connexion, $this->object->id);
        //====================================================================//
        // Remove All Deleted Barcodes
        foreach (array_diff($current, $new) as $resourceId => $barcode) {
            $expected++;
            $success += (int) BarcodeManager::remove($connexion, $resourceId);
        }
        //====================================================================//
        // Create All Added Barcodes
        foreach (array_diff($new, $current) as $barcode) {
            $expected++;
            $success += (int) BarcodeManager::add($connexion, $this->object->id, $barcode);
        }

        return ($expected == $success);
    }
}
