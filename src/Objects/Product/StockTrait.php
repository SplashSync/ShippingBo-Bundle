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

namespace Splash\Connectors\ShippingBo\Objects\Product;

use Exception;
use Splash\Client\Splash;

/**
 * Product Stocks Trait
 */
trait StockTrait
{
    /**
     * Build Status Fields
     *
     * @return void
     */
    protected function buildStockFields(): void
    {
        //====================================================================//
        // Stock Reel
        $this->fieldsFactory()->create(SPL_T_INT)
            ->identifier("stock_available")
            ->name("Available Stock")
            ->microData("http://schema.org/Offer", "inventoryLevel")
            ->isReadOnly(!$this->connector->isSandbox())
        ;
    }

    /**
     * Read requested Field
     *
     * @param string $key       Input List Key
     * @param string $fieldName Field Identifier / Name
     */
    protected function getStockFields(string $key, string $fieldName): void
    {
        //====================================================================//
        // READ Fields
        switch ($fieldName) {
            case 'stock_available':
                $this->out[$fieldName] = (int) $this->object->stock;

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
     * @param mixed  $fieldData Field Data
     *
     * @throws Exception
     */
    protected function setStockFields(string $fieldName, $fieldData): void
    {
        //====================================================================//
        // WRITE Field
        switch ($fieldName) {
            case 'stock_available':
                //====================================================================//
                // Compare Stocks
                $variation = (int) ($fieldData - $this->object->stock);
                if (0 == $variation) {
                    break;
                }
                //====================================================================//
                // Add Stocks Variation
                $uri = "/stock_variations";
                $body = array(
                    "product_id" => (int) $this->object->id,
                    "variation" => $variation,
                );
                if (!$this->getVisitor()->getConnexion()->post($uri, $body)) {
                    Splash::log()->err("An error occurred while updating Product Stock");

                    break;
                }
                Splash::log()->war("Product Stock Updated");

                break;
        }
        unset($this->in[$fieldName]);
    }
}
