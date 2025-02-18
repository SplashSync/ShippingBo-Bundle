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

namespace App\Entity\SupplyCapsule;

use ApiPlatform\Metadata as Meta;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Provides supplier information including internal ID, name, and code.
 */
trait SupplierTrait
{
    /**
     * The Internal ID of the Supplier.
     */
    #[Assert\Type("string")]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Groups(array('read', 'write'))]
    #[Meta\ApiProperty(default: "1234")]
    public ?string $supplierId = null;

    /**
     * The Name of the Supplier.
     */
    #[Assert\NotNull]
    #[Assert\Type("string")]
    #[ORM\Column(type: Types::STRING)]
    #[Groups(array('read', 'write'))]
    #[Meta\ApiProperty(default: "SupplierName")]
    public string $supplierName;

    /**
     * The Code of the Supplier.
     */
    #[Assert\NotNull]
    #[Assert\Type("string")]
    #[ORM\Column(type: Types::STRING)]
    #[Groups(array('read', 'write'))]
    #[Meta\ApiProperty(default: "SupplierCode")]
    public string $supplierCode;
}
