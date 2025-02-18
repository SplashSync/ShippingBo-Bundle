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

namespace Splash\Connectors\ShippingBo\Models\Metadata\SupplyCapsule;

use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Provides supplier information including internal ID, name, and code.
 */
trait SupplierTrait
{
    /**
     * The Internal ID of the Supplier.
     */
    #[
        Assert\Type("string"),
        JMS\SerializedName("supplier_id"),
        JMS\Groups(array("Read")),
        JMS\Type("string"),
        SPL\Field(
            name: "Supplier ID",
            desc: "Internal ID of the supplier.",
            group: "Supplier"
        ),
        SPL\IsReadOnly,
    ]
    public ?string $supplierId = null;

    /**
     * The Name of the Supplier.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("supplier_name"),
        JMS\Groups(array("Read", "Write", "List", "Required")),
        JMS\Type("string"),
        SPL\Field(
            name: "Supplier Name",
            desc: "The name of the supplier.",
            group: "Supplier"
        ),
        SPL\Microdata("http://schema.org/Organization", "legalName"),
        SPL\Flags(listed: true),
        SPL\IsRequired,
    ]
    public string $supplier_name;

    /**
     * The Code of the Supplier.
     */
    #[
        Assert\NotNull,
        Assert\Type("string"),
        JMS\SerializedName("supplier_code"),
        JMS\Groups(array("Read", "Write", "Required")),
        JMS\Type("string"),
        SPL\Field(
            name: "Supplier Code",
            desc: "The code of the supplier.",
            group: "Supplier"
        ),
        SPL\Microdata("http://schema.org/Organization", "identifier"),
        SPL\IsRequired,
    ]
    public string $supplierCode;
}
