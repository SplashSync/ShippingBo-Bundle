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

namespace Splash\Connectors\ShippingBo\Models\Api\Product;

use JMS\Serializer\Annotation as JMS;
use Splash\OpenApi\Validator as SPL;
use Symfony\Component\Validator\Constraints as Assert;

trait AdminUrlsTrait
{
    /**
     * @var string
     *
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("admin_url")
     *
     * @JMS\Groups ({"Read"})
     *
     * @JMS\Type("string")
     *
     * @JMS\Accessor("string")
     *
     * @SPL\Type("url")
     *
     * @SPL\Group("Meta")
     *
     * @SPL\Microdata({"https://schema.org/Product", "adminUrl"})
     */
    public string $admin_url;

    /**
     * @JMS\PostDeserialize()
     */
    public function setupAdminUrl(): void
    {
        $this->admin_url = sprintf(
            "https://app.shippingbo.com/#/products/%s/summary",
            $this->id ?? "new"
        );
    }
}
