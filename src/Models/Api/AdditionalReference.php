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

namespace Splash\Connectors\ShippingBo\Models\Api;

use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing a Product Additional Reference.
 *
 * @SuppressWarnings(PHPMD.CamelCasePropertyName)
 */
class AdditionalReference
{
    //====================================================================//
    // SBO CORE DATA
    use Core\SboCoreTrait;

    /**
     * Unique Identifier.
     *
     * @Assert\NotNull()
     *
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("id")
     *
     * @JMS\Type("string")
     *
     * @JMS\Groups({"Read"})
     */
    public ?string $id = null;

    /**
     * Matched Quantities.
     *
     * @Assert\NotNull()
     *
     * @Assert\Type("integer")
     *
     * @JMS\SerializedName("matched_quantity")
     *
     * @JMS\Groups({"Read"})
     *
     * @JMS\Type("integer")
     */
    public int $matchedQuantity = 1;

    /**
     * Field for Orders Item.
     *
     * @Assert\NotNull()
     *
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("order_item_field")
     *
     * @JMS\Type("string")
     *
     * @JMS\Groups({"Read"})
     */
    public string $orderItemField = "product_ref";

    /**
     * Field for Orders Value.
     *
     * @var string
     *
     * @Assert\NotNull()
     *
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("order_item_value")
     *
     * @JMS\Type("string")
     *
     * @JMS\Groups({"Read"})
     */
    public string $orderItemValue;

    /**
     * Product Target Field.
     *
     * @Assert\NotNull()
     *
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("product_field")
     *
     * @JMS\Type("string")
     *
     * @JMS\Groups({"Read"})
     */
    public string $productField = "id";

    /**
     * Product Target Value.
     *
     * @Assert\NotNull()
     *
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("product_value")
     *
     * @JMS\Type("string")
     *
     * @JMS\Groups({"Read"})
     */
    public ?string $productValue = null;

    public function __toString(): string
    {
        return (string) $this->orderItemValue;
    }

    public function toArray(): array
    {
        return array(
            "matched_quantity" => $this->matchedQuantity,
            "order_item_field" => $this->orderItemField,
            "order_item_value" => $this->orderItemValue,
            "product_field" => $this->productField,
            "product_value" => $this->productValue,
        );
    }
}
