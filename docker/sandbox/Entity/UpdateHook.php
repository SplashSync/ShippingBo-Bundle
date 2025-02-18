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
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing Product Warehouse Slot model.
 */
#[ORM\Entity]
#[ORM\Table(name: "webhook")]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: array(
        new Meta\GetCollection(),
        new Meta\Get(),
        new Meta\Post(),
        new Meta\Patch(),
        new Meta\Delete(),
    ),
    normalizationContext: array("groups" => array("read")),
    denormalizationContext: array("groups" => array("write")),
)]
class UpdateHook implements SboObjectInterface
{
    /**
     * Unique Identifier.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\Type("integer")]
    #[Groups(array("read"))]
    public int $id;

    /**
     * Activated Flag
     */
    #[ORM\Column(type: Types::BOOLEAN)]
    #[Assert\Type("boolean")]
    #[Groups(array("read", "write"))]
    public bool $activated = false;

    /**
     * Visible Flag
     */
    #[ORM\Column(type: Types::BOOLEAN)]
    #[Assert\Type("boolean")]
    #[Groups(array("read", "write"))]
    public bool $visible = false;

    /**
     * Trigger on Destroy Flag
     */
    #[ORM\Column(type: Types::BOOLEAN)]
    #[Assert\Type("boolean")]
    #[Groups(array("read", "write"))]
    public bool $triggerOnDestroy = false;

    /**
     * EndPoint Url
     */
    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotNull]
    #[Assert\Type("string")]
    #[Groups(array("read", "write"))]
    public string $endpointUrl;

    /**
     * Object Type
     */
    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotNull]
    #[Assert\Type("string")]
    #[Groups(array("read", "write"))]
    public string $objectClass;

    /**
     * {@inheritDoc}
     */
    public static function getItemIndex(): string
    {
        return "update_hook";
    }

    /**
     * {@inheritDoc}
     */
    public static function getCollectionIndex(): string
    {
        return "update_hooks";
    }
}
