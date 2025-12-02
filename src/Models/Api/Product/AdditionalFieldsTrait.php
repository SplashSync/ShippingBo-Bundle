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
use Splash\Connectors\ShippingBo\Models\Api\AdditionalField;
use Splash\OpenApi\Validator as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Access to Product Additional Fields
 */
trait AdditionalFieldsTrait
{
    /**
     * Product Additional Fields List.
     *
     * @var AdditionalField[]
     *
     * @JMS\SerializedName("product_additional_fields")
     *
     * @JMS\Type("array<Splash\Connectors\ShippingBo\Models\Api\AdditionalField>")
     *
     * @JMS\Groups ({"Read"})
     *
     * @Assert\All({
     *
     *   @Assert\Type("Splash\Connectors\ShippingBo\Models\Api\AdditionalField")
     * })
     *
     * @SPL\Group("Fields")
     */
    public array $additionalFields = array();
}
