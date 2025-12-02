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

namespace Splash\Connectors\ShippingBo\Services;

use Psr\Container\ContainerInterface;
use Splash\Connectors\ShippingBo\Models\Connector\ShippingBoConnectorAwareTrait;
use Splash\Connectors\ShippingBo\Services\SupplyCapsule\SupplyCapsuleItemsManager;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Webmozart\Assert\Assert;

/**
 * ShippingBo Services Locator
 */
class ShippingBoLocator implements ServiceSubscriberInterface
{
    use ShippingBoConnectorAwareTrait;

    public function __construct(
        private ContainerInterface $locator,
    ) {
    }

    public static function getSubscribedServices(): array
    {
        return array(
            //====================================================================//
            // Product Data Managers
            Product\WarehouseSlotsManager::class,
            Product\AdditionalFieldsManager::class,
            //====================================================================//
            // Supplier Orders Managers
            SupplyCapsuleItemsManager::class,
            //====================================================================//
            // Other Managers
            WebhooksManager::class,
        );
    }

    //====================================================================//
    // Product Data Managers
    //====================================================================//

    /**
     * Get Warehouse Slots Manager
     */
    public function getWarehouseSlotsManager(): Product\WarehouseSlotsManager
    {
        Assert::isInstanceOf(
            $service = $this->locator->get(Product\WarehouseSlotsManager::class),
            Product\WarehouseSlotsManager::class
        );

        return $service->configure($this->connector);
    }

    /**
     * Get Additional Fields Manager
     */
    public function getAdditionalFieldsManager(): Product\AdditionalFieldsManager
    {
        Assert::isInstanceOf(
            $service = $this->locator->get(Product\AdditionalFieldsManager::class),
            Product\AdditionalFieldsManager::class
        );

        return $service->configure($this->connector);
    }

    //====================================================================//
    // Supplier Orders Managers
    //====================================================================//

    /**
     * Get Webhooks Manager
     */
    public function getSupplyCapsuleItemsManager(): SupplyCapsuleItemsManager
    {
        Assert::isInstanceOf(
            $service = $this->locator->get(SupplyCapsuleItemsManager::class),
            SupplyCapsuleItemsManager::class
        );

        return $service->configure($this->connector);
    }

    //====================================================================//
    // Other Managers
    //====================================================================//

    /**
     * Get Webhooks Manager
     */
    public function getWebhooksManager(): WebhooksManager
    {
        Assert::isInstanceOf(
            $service = $this->locator->get(WebhooksManager::class),
            WebhooksManager::class
        );

        return $service->configure($this->connector);
    }
}
