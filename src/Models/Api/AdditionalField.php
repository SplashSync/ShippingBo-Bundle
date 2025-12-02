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
 * Class representing a Product Additional Field.
 */
class AdditionalField
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
     * Field Parent Product ID.
     *
     * @Assert\NotNull()
     *
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("product_id")
     *
     * @JMS\Type("string")
     *
     * @JMS\Groups({"Read"})
     */
    public ?string $productId = null;

    /**
     * Additional Field Key.
     *
     * @Assert\NotNull()
     *
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("key")
     *
     * @JMS\Groups({"Read"})
     *
     * @JMS\Type("string")
     */
    public string $key;

    /**
     * Additional Field Value.
     *
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("value")
     *
     * @JMS\Groups({"Read"})
     *
     * @JMS\Type("string")
     */
    public ?string $value = null;

    /**
     * Mark this Field as Updated.
     *
     * @JMS\Exclude
     */
    private bool $updated = false;

    public function __construct(string $productId, string $key)
    {
        $this->productId = $productId;
        $this->key = $key;
    }

    /**
     * Convert to String.
     */
    public function __toString(): string
    {
        return $this->key;
    }

    /**
     * Update this Field Value.
     */
    public function update(null|string|bool $value, ?string $fieldType = null): bool
    {
        if ((SPL_T_BOOL == ($fieldType ?? SPL_T_VARCHAR)) || is_bool($value)) {
            $value = $value ? "1" : "0";
        }

        if ($this->value !== $value) {
            $this->value = $value;
            $this->updated = true;
        }

        return $this->updated;
    }

    /**
     * Convert to Array.
     */
    public function toArray(): array
    {
        return array(
            "key" => $this->key,
            "value" => $this->value,
            "product_id" => $this->productId,
        );
    }

    /**
     * Check if this Field is New.
     */
    public function isNew(): bool
    {
        return empty($this->id) && !$this->isEmpty();
    }

    /**
     * Check if this Field is Updated.
     */
    public function isUpdated(): bool
    {
        return !empty($this->id) && !$this->isEmpty() && $this->updated;
    }

    /**
     * Check if this Field is top Delete.
     */
    public function isToDelete(): bool
    {
        return !empty($this->id) && $this->isEmpty() && $this->updated;
    }

    /**
     * Check if this Field is Empty.
     */
    private function isEmpty(): bool
    {
        return empty($this->value) && ("0" !== $this->value && "false" !== $this->value);
    }
}
