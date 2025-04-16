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
use Splash\Connectors\ShippingBo\Helpers\AdditionalReferenceManager;
use Splash\Connectors\ShippingBo\Models\Api\AdditionalReference;
use Splash\Models\Helpers\InlineHelper;

/**
 * Manage Product Additional References Fields
 */
trait AdditionalReferencesTrait
{
    /**
     * Build Status Fields
     *
     * @return void
     */
    protected function buildAdditionalReferencesFields(): void
    {
        //====================================================================//
        // Product Multi-Ean Barcodes
        $this->fieldsFactory()->create(SPL_T_INLINE)
            ->identifier("additional_refs")
            ->name("Add. Refs");
    }

    /**
     * Read requested Field
     */
    protected function getAdditionalReferencesFields(string $key, string $fieldName): void
    {
        //====================================================================//
        // READ Fields
        switch ($fieldName) {
            case 'additional_refs':
                $this->out[$fieldName] = InlineHelper::fromArray(
                    $this->getAdditionalReferences()
                );

                break;
            default:
                return;
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
    protected function setAdditionalReferencesFields(string $fieldName, ?string $fieldData): void
    {
        //====================================================================//
        // WRITE Field
        switch ($fieldName) {
            case 'additional_refs':
                $toAddRefs = InlineHelper::toArray($fieldData);
                $toRemoveIds = array();
                //====================================================================//
                // Walk on Current References
                foreach ($this->object->additionalReferences as $addRef) {
                    $reference = (string) $addRef;
                    //====================================================================//
                    // Should Reference be Removed ??
                    if (!in_array($reference, $toAddRefs, true)) {
                        $toRemoveIds[] = (string) $addRef->id;
                    } else {
                        unset($toAddRefs[array_search($reference, $toAddRefs, true)]);
                    }
                }
                //====================================================================//
                // Walk on IDs to Remove
                foreach ($toRemoveIds as $toRemoveId) {
                    AdditionalReferenceManager::remove($this->visitor->getConnexion(), $toRemoveId);
                }
                //====================================================================//
                // Walk on References to Add
                foreach ($toAddRefs as $toAddRef) {
                    $addRefObject = AdditionalReferenceManager::create($this->object->id, $toAddRef);
                    AdditionalReferenceManager::add($this->visitor->getConnexion(), $addRefObject);
                }

                break;
        }
        unset($this->in[$fieldName]);
    }

    /**
     * Get Product Additional References Array
     *
     * @return string[]
     */
    protected function getAdditionalReferences(): array
    {
        return array_map(
            fn (AdditionalReference $addRef) => (string) $addRef,
            $this->object->additionalReferences
        );
    }
}
