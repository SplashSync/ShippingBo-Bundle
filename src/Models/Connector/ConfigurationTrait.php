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

namespace Splash\Connectors\ShippingBo\Models\Connector;

use Splash\Connectors\ShippingBo\Actions;
use Splash\Connectors\ShippingBo\Actions\Webhooks\Receive;
use Splash\Connectors\ShippingBo\Form\DebugFormType;
use Splash\Connectors\ShippingBo\Form\EditFormType;

/**
 * Connector Configuration
 */
trait ConfigurationTrait
{
    //====================================================================//
    // Profile Interfaces
    //====================================================================//

    /**
     * Get Connector Profile Information
     *
     * @return array
     */
    public function getProfile() : array
    {
        return array(
            'enabled' => true,                                      // is Connector Enabled
            'beta' => false,                                        // is this a Beta release
            'type' => self::TYPE_HIDDEN,                            // Connector Type or Mode
            'name' => 'shippingbo',                                 // Connector code (lowercase, no space allowed)
            'connector' => 'splash.connectors.shippingbo',          // Connector Symfony Service
            'title' => 'profile.card.title',                        // Public short name
            'label' => 'profile.card.label',                        // Public long name
            'domain' => 'ShippingBoBundle',                         // Translation domain for names
            'ico' => '/bundles/shippingbo/img/ShippingBo-Icon.jpg', // Public Icon path
            'www' => 'https://shippingbo.com',                      // Website Url
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getConnectedTemplate() : string
    {
        return "@ShippingBo/Profile/connected.html.twig";
    }

    /**
     * {@inheritdoc}
     */
    public function getOfflineTemplate() : string
    {
        return "@ShippingBo/Profile/offline.html.twig";
    }

    /**
     * {@inheritdoc}
     */
    public function getNewTemplate() : string
    {
        return "@ShippingBo/Profile/new.html.twig";
    }

    /**
     * {@inheritdoc}
     */
    public function getFormBuilderName() : string
    {
        $this->selfTest();

        if ($this->isSandbox()) {
            return DebugFormType::class;
        }

        return EditFormType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getMasterAction(): ?string
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getPublicActions() : array
    {
        return array(
            "index" => Receive::class,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getSecuredActions() : array
    {
        return array(
            "update-wh-slots" => Actions\FetchWarehouseSlots::class,
            "update-webhooks" => Actions\Webhooks\Configure::class,
        );
    }
}
