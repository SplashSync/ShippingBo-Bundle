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

namespace Splash\Connectors\ShippingBo\Services\Product;

use Splash\Components\StringConverter;
use Splash\Connectors\ShippingBo\Dictionary\ProductAdditionalFields;
use Splash\Connectors\ShippingBo\Models\Api\AdditionalField;
use Splash\Connectors\ShippingBo\Models\Api\Product;
use Splash\Connectors\ShippingBo\Models\Connector\ShippingBoConnectorAwareTrait;
use Splash\Connectors\ShippingBo\Services\ShippingBoConnector;
use Splash\Core\SplashCore as Splash;

/**
 * Manage Product Additional Fields
 */
class AdditionalFieldsManager
{
    use ShippingBoConnectorAwareTrait;

    /**
     * Prefix for Additional Fields Identifiers
     */
    const PREFIX = "additional_field_";

    /**
     * Cache for Canonical Fields Types
     *
     * @return array<string, string>
     */
    private ?array $canonicalTypes = null;

    /**
     * Cache for Canonical Fields Names
     *
     * @return array<string, string>
     */
    private ?array $canonicalNames = null;

    /**
     * Configure with Current API Connexion Settings
     */
    public function configure(ShippingBoConnector $connector): static
    {
        $this->canonicalTypes = null;
        $this->canonicalNames = null;
        $this->connector = $connector;

        return $this;
    }

    /**
     * Get List of Configured Additional Fields
     *
     * @return array<string, string>
     */
    public function getConfiguredFields(): array
    {
        $fields = $this->connector->getParameter(ProductAdditionalFields::KEY, array());
        if (!is_array($fields)) {
            return array();
        }

        return $fields;
    }

    /**
     * Get Configured Additional Field Type
     */
    public function getConfiguredFieldType(string $fieldKey): string
    {
        $this->canonicalTypes = array_combine(
            array_map(
                fn ($key) => (string) StringConverter::canonicalString($key),
                array_keys($this->getConfiguredFields())
            ),
            array_values($this->getConfiguredFields())
        );

        return $this->canonicalTypes[$fieldKey] ?? SPL_T_VARCHAR;
    }

    /**
     * Get Configured Additional Field Name
     */
    public function getConfiguredFieldName(string $fieldKey): string
    {
        $this->canonicalNames = array_combine(
            array_map(
                fn ($key) => (string) StringConverter::canonicalString($key),
                array_keys($this->getConfiguredFields())
            ),
            array_keys($this->getConfiguredFields())
        );

        return $this->canonicalNames[$fieldKey] ?? ucwords($fieldKey);
    }

    /**
     * Get Product Additional Field Identifier
     */
    public function getFieldId(string $key): string
    {
        return sprintf("%s%s", self::PREFIX, StringConverter::canonicalString($key));
    }

    /**
     * Get Product Additional Field Storage Key from Splash Identifier
     *
     * @return null|string null or Canonical Field ID
     */
    public function getFieldKey(string $fieldId): ?string
    {
        if (!str_starts_with($fieldId, "additional_field_")) {
            return null;
        }

        return str_replace("additional_field_", "", $fieldId) ?: null;
    }

    /**
     * Update Supply Capsule Items after Main Update
     *
     * @return null|bool
     */
    public function updateFields(Product $product): ?bool
    {
        //====================================================================//
        // Walk on All Additional Fields
        foreach ($product->additionalFields as $additionalField) {
            //====================================================================//
            // Update All Modified Fields
            if ($additionalField->isUpdated()) {
                $this->updateAdditionalField($additionalField);
            }
            //====================================================================//
            // Create All Inserted Fields
            if ($additionalField->isNew()) {
                $this->createAdditionalField($additionalField);
            }
            //====================================================================//
            // Delete All Empty Fields
            if ($additionalField->isToDelete()) {
                $this->deleteAdditionalField($additionalField);
            }
        }

        return true;
    }

    /**
     * Create Item
     */
    private function createAdditionalField(AdditionalField $additionalField): bool
    {
        //====================================================================//
        // Safety Check
        if (!$additionalField->isNew()) {
            return Splash::log()->err(sprintf("Invalid New %s", $additionalField));
        }
        //====================================================================//
        // Execute Create Request
        $response = $this->connector->getConnexion()->post(
            "/product_additional_fields",
            $additionalField->toArray()
        );
        if (!$response) {
            return Splash::log()->err(
                sprintf("Unable to create Additional Field %s", $additionalField)
            );
        }

        return true;
    }

    /**
     * Update Additional Field
     */
    private function updateAdditionalField(AdditionalField $additionalField): bool
    {
        //====================================================================//
        // Safety Check
        if ($additionalField->isNew()) {
            return Splash::log()->err(sprintf("Invalid Updated %s", $additionalField));
        }
        //====================================================================//
        // Execute Update Request
        $response = $this->connector->getConnexion()->patch(
            sprintf("/product_additional_fields/%s", $additionalField->id),
            $additionalField->toArray()
        );
        if (!$response) {
            return Splash::log()->err(
                sprintf("Unable to update Additional Field %s", $additionalField)
            );
        }

        return true;
    }

    /**
     * Delete Additional Field
     */
    private function deleteAdditionalField(AdditionalField $additionalField): bool
    {
        //====================================================================//
        // Safety Check
        if (!$additionalField->isToDelete()) {
            return Splash::log()->err(sprintf("Invalid to Delete %s", $additionalField));
        }
        if (!$additionalField->id) {
            return true;
        }
        //====================================================================//
        // Execute Update Request
        $response = $this->connector->getConnexion()->delete(
            sprintf("/product_additional_fields/%s", $additionalField->id),
        );
        if (is_null($response)) {
            return Splash::log()->err(
                sprintf("Unable to delete Additional Field %s", $additionalField)
            );
        }

        return true;
    }
}
