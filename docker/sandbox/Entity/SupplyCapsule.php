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

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata as Meta;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Represents the SupplyCapsule entity.
 */
#[ORM\Entity()]
#[ORM\Table("supply_capsules")]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: array(
        new Meta\GetCollection(),
        new Meta\Get(),
        new Meta\Patch(),
        new Meta\Post(),
        new Meta\Delete(),
    ),
    normalizationContext: array('groups' => array('read')),
    denormalizationContext: array('groups' => array('write'))
)]
#[ApiFilter(SearchFilter::class, properties: array('sourceRef' => 'exact'))]
class SupplyCapsule implements SboObjectInterface
{
    use Core\SboCoreTrait;
    use Core\SboSourceTrait;

    use SupplyCapsule\DatesTrait;
    use SupplyCapsule\SupplierTrait;
    use SupplyCapsule\StatusTrait;

    /**
     * @var Collection<SupplyCapsuleItem> List of Supply Capsule Items.
     */
    #[Assert\All(array(
        new Assert\Type(SupplyCapsuleItem::class)
    ))]
    #[Groups(array('read'))]
    #[ORM\OneToMany(mappedBy: 'order', targetEntity: SupplyCapsuleItem::class, cascade: array('all'))]
    public Collection $supplyCapsuleItems;

    public function __construct()
    {
        $this->supplyCapsuleItems = new ArrayCollection();
    }

    /**
     * @inheritDoc
     */
    public static function getItemIndex(): ?string
    {
        return "supply_capsule";
    }

    /**
     * @inheritDoc
     */
    public static function getCollectionIndex(): ?string
    {
        return "supply_capsules";
    }
}
