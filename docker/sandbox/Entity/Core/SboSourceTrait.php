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

namespace App\Entity\Core;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Object Source Trait
 */
trait SboSourceTrait
{
    /**
     * Technical - API Source Name.
     */
    #[Assert\NotNull]
    #[Assert\Type('string')]
    #[ORM\Column(type: Types::STRING)]
    #[Groups(array('read', 'write'))]
    public string $source;

    /**
     * Technical - API Source Object Reference.
     */

    #[Assert\NotNull]
    #[Assert\Type('string')]
    #[ORM\Column(type: Types::STRING)]
    #[Groups(array('read', 'write'))]
    public string $sourceRef;
}
