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

namespace Splash\Connectors\ShippingBo\Objects\Product;

use Exception;
use Splash\Components\StringConverter;
use Splash\Connectors\ShippingBo\Dictionary\ProductAdditionalFields;
use Splash\Connectors\ShippingBo\Models\Api\AdditionalField;
use Splash\Connectors\ShippingBo\Services\Product\AdditionalFieldsManager;

/**
 * Manage Product Additional Fields Access
 */
trait AdditionalFieldsTrait
{
    /**
     * Build Additional Fields from Manager Listing
     *
     * @return void
     */
    protected function buildAdditionalDataFields(): void
    {
        $manager = $this->getAdditionalFieldsManager();
        //====================================================================//
        // Walk on Configured Additional Fields
        foreach ($manager->getConfiguredFields() as $fieldKey => $fieldType) {
            //====================================================================//
            // Safety Check
            if (!$fieldKey || !is_string($fieldKey)) {
                continue;
            }
            //====================================================================//
            // Register Field
            $this->fieldsFactory()->create($fieldType)
                ->identifier($manager->getFieldId($fieldKey))
                ->name(sprintf("[C] %s", ucwords($fieldKey)))
                ->group("Other Fields")
                ->microData("http://schema.org/Product", ucfirst($fieldKey))
            ;
        }
    }

    /**
     * Read the Requested Field
     */
    protected function getAdditionalDataFields(string $key, string $fieldName): void
    {
        $manager = $this->getAdditionalFieldsManager();
        //====================================================================//
        // Filter Others Fields
        if (!$fieldKey = $manager->getFieldKey($fieldName)) {
            return;
        }
        //====================================================================//
        // Get Additional Field Type
        $fieldType = $manager->getConfiguredFieldType($fieldKey);

        //====================================================================//
        // Walk on Additional Fields
        $this->out[$fieldName] = null;
        foreach ($this->object->additionalFields ?? array() as $additionalField) {
            //====================================================================//
            // This is Searched Field
            if (StringConverter::canonicalString($additionalField->key) != $fieldKey) {
                continue;
            }
            //====================================================================//
            // This is Searched Field
            if (SPL_T_BOOL == $fieldType) {
                $this->out[$fieldName] = !empty($additionalField->value);
            } else {
                $this->out[$fieldName] = (string) $additionalField->value;
            }
        }

        unset($this->in[$key]);
    }

    /**
     * Write Given Fields
     *
     * @param string      $fieldName Field Identifier / Name
     * @param null|string $fieldData Field Data
     *
     * @throws Exception
     */
    protected function setAdditionalDataFields(string $fieldName, ?string $fieldData): void
    {
        $manager = $this->getAdditionalFieldsManager();
        //====================================================================//
        // Filter Others Fields
        if (!$fieldKey = $manager->getFieldKey($fieldName)) {
            return;
        }
        //====================================================================//
        // Get Additional Field Type
        $fieldType = $manager->getConfiguredFieldType($fieldKey);

        //====================================================================//
        // Walk on Additional Fields
        $toUpdateField = null;
        foreach ($this->object->additionalFields as $additionalField) {
            //====================================================================//
            // This is Searched Field
            if (StringConverter::canonicalString($additionalField->key) != $fieldKey) {
                continue;
            }
            //====================================================================//
            // This is Searched Field
            $toUpdateField = $additionalField;

            break;
        }
        //====================================================================//
        // Create Field if Not Found
        if (!$toUpdateField) {
            $toUpdateField = new AdditionalField(
                $this->object->id,
                $manager->getConfiguredFieldName($fieldKey)
            );
            $this->object->additionalFields[] = $toUpdateField;
        }
        //====================================================================//
        // Update Field Value
        if ($toUpdateField->update($fieldData, $fieldType)) {
            $this->needUpdate(ProductAdditionalFields::KEY);
        }

        unset($this->in[$fieldName]);
    }

    /**
     * Get Product Additional Field Identifier
     */
    private function getAdditionalFieldsManager(): AdditionalFieldsManager
    {
        return $this->connector
            ->getLocator()
            ->getAdditionalFieldsManager()
        ;
    }
}
