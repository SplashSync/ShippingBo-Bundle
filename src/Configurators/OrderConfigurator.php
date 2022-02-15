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

namespace Splash\Connectors\ShippingBo\Configurators;

use Splash\Client\Splash;
use Splash\Models\AbstractConfigurator;

/**
 * Main Order Fields Configurator
 */
class OrderConfigurator extends AbstractConfigurator
{
    const CONFIGURATION = array(
        //====================================================================//
        // System - Metadata
        //====================================================================//
        "created_at" => array(
            'write' => false,
            'required' => false,
            "itemtype" => "http://schema.org/DataFeedItem",
            "itemprop" => "dateOrderCreated",
            'group' => "Meta",
        ),

        //====================================================================//
        // System - Read Only
        //====================================================================//

        "source" => array('read' => true, "write" => false),
        "state" => array('required' => false, "write" => false),
        "source@items" => array('read' => true, "write" => false),
        "source_ref@items" => array('read' => true, "write" => false),
        "shipping_address_id" => array("notest" => true),

        //====================================================================//
        // System - Excluded
        //====================================================================//

        "shippingAddress__id" => array('excluded' => true),
        "billingAddress__id" => array('excluded' => true),
    );

    const DEBUG = array(
        // Disable Updates for Country
        "shippingAddress__country" => array("write" => false),
        // Disable Tests for Discounts
        "discount@items" => array("notest" => true, "write" => false),
        // Force Testing of Order States Changes
        "state" => array('required' => false, "write" => true),
    );

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(): array
    {
        return array(
            "Order" => array("fields" => array_replace_recursive(
                self::CONFIGURATION,
                Splash::isDebugMode() ? self::DEBUG : array()
       )));
    }
}
