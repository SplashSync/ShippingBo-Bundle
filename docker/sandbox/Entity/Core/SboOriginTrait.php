<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) 2015-2021 Splash Sync  <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace App\Entity\Core;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Object Origin Trait
 */
trait SboOriginTrait
{
    /**
     * Technical - Data Origin Source Name.
     *
     * @var string
     * @Assert\NotNull()
     * @Assert\Type("string")
     *
     * @ORM\Column(type="string")
     *
     * @Groups({"read", "write"})
     */
    public string $origin;

    /**
     * Technical - Data Origin Source Name.
     *
     * @var string
     * @Assert\NotNull()
     * @Assert\Type("string")
     *
     * @ORM\Column(type="string")
     *
     * @Groups({"read", "write"})
     */
    public string $origin_ref;

    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\NotNull()
     * @Assert\Type("DateTime")
     *
     * @Groups({"read", "write"})
     */
    public DateTime $origin_created_at;
}
