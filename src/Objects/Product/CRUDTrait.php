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
use Splash\Connectors\ShippingBo\Models\Api\Product;
use Splash\OpenApi\Models\Objects\CRUDTrait as OpenApiCRUDTrait;

/**
 * ShippingBo Product CRUD Functions
 */
trait CRUDTrait
{
    use OpenApiCRUDTrait{
        OpenApiCRUDTrait::create as coreCreate;
    }

    /**
     * @throws Exception
     *
     * @return false|Product
     */
    public function create()
    {
        //====================================================================//
        // Ensure Default Source
        $this->in['source'] = $this->in['source'] ?? "SplashSync";
        //====================================================================//
        // Execute Core Action
        return $this->coreCreate();
    }
}
