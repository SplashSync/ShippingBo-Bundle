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

use Splash\OpenApi\Action\Json\ListAction;

/**
 * Search for Supply Capsule by Primary Key
 */
trait PrimaryTrait
{
    /**
     * @inheritDoc
     */
    public function getByPrimary(array $keys): ?string
    {
        //====================================================================//
        // Safety Check
        $sourceRef = $keys['source_ref'] ?? null;
        if (!$sourceRef && is_string($sourceRef)) {
            return null;
        }
        //====================================================================//
        // Configure List Action for Primary Request
        $this->visitor->setListAction(
            ListAction::class,
            array(
                "filterKey" => $this->connector->isSandbox()
                    ? "sourceRef"
                    : "search[source_ref__eq][]"
                ,
            )
        );
        //====================================================================//
        // Search by User Ref
        $productsList = $this->objectsList($sourceRef);
        if (1 != $productsList['meta']['current']) {
            return null;
        }
        //====================================================================//
        // Search In Results
        $firstProductId = array_shift($productsList)['id'] ?? null;
        if (empty($firstProductId) || !is_string($firstProductId)) {
            return null;
        }

        return $firstProductId;
    }
}
