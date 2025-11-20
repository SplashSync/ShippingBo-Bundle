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

use Splash\Connectors\ShippingBo\Dictionary\SupplierFilterModes;
use Splash\Core\SplashCore as Splash;

/**
 * Filter Sbo Product Creation by Supplier Name
 */
trait SupplierFilterTrait
{
    /**
     * Check if this Object is Blocked Supplier Filter
     */
    protected function isFilteredBySupplier(?string $supplierName): ?bool
    {
        //====================================================================//
        // Check if Suppliers are Selected
        $knownSuppliers = $this->connector->getParameter(SupplierFilterModes::KEY);
        if (!is_array($knownSuppliers) || empty($knownSuppliers)) {
            return null;
        }
        //====================================================================//
        // Check If received Supplier is Given
        if (empty($supplierName)) {
            return null;
        }
        //====================================================================//
        // Identify Supplier by Name
        $supplierName = strtolower(trim($supplierName));

        //====================================================================//
        // Identify Supplier by Name
        return match ($this->getSupplierFiltersModes()[$supplierName] ?? null) {
            SupplierFilterModes::BLOCK => true,
            default => null,
        };
    }

    /**
     * Mark Object as Filtered & Return Details in Log
     *
     * @return null
     */
    protected function logFilteredBySupplier()
    {
        return Splash::log()->errNull("This Product is Filtered by Supplier.");
    }

    /**
     * Get & Normalize Supplier Filter Modes
     *
     * @return array<string, string>
     */
    private function getSupplierFiltersModes(): array
    {
        //====================================================================//
        // Check if Suppliers are Selected
        $knownSuppliers = $this->connector->getParameter(SupplierFilterModes::KEY);
        if (!is_array($knownSuppliers) || empty($knownSuppliers)) {
            return array();
        }

        return array_combine(
            array_map(fn ($key) => strtolower(trim($key)), array_keys($knownSuppliers)),
            $knownSuppliers
        );
    }
}
