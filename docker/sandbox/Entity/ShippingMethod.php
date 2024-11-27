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

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata as Meta;
use App\Entity\Core\SboCoreTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing Product Warehouse Slot Content model.
 *
 * @ORM\Entity()
 *
 * @ORM\Table(name="shipping_methods")
 *
 * @ORM\HasLifecycleCallbacks()
 */
#[ApiResource(
    operations: array(
        new Meta\GetCollection(
            uriTemplate:    '/shipping_methods',
        ),
    ),
    normalizationContext: array("groups" => array("read")),
    denormalizationContext: array("groups" => array("write"))
)]
class ShippingMethod implements SboObjectInterface
{
    use SboCoreTrait;

    /**
     * Shipping Method Name
     *
     * @Assert\NotNull()
     *
     * @Assert\Type("string")
     *
     * @ORM\Column(type="string")
     *
     * @Groups({"read", "write"})
     */
    public string $name;

    /**
     * Carrier ID
     *
     * @Assert\NotNull()
     *
     * @Assert\Type("integer")
     *
     * @ORM\Column(type="integer")
     *
     * @Groups({"read", "write"})
     */
    public int $carrier_id;

    //====================================================================//
    // JSON SERIALIZER
    //====================================================================//

    /**
     * {@inheritDoc}
     */
    public static function getItemIndex(): string
    {
        return "shipping_method";
    }

    /**
     * {@inheritDoc}
     */
    public static function getCollectionIndex(): string
    {
        return "shipping_methods";
    }
}
