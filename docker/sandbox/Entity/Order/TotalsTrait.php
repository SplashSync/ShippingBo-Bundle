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

namespace App\Entity\Order;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Order Totals
 */
trait TotalsTrait
{
    //====================================================================//
    // GRAND TOTALS
    //====================================================================//

    #[Assert\NotNull]
    #[Assert\Type("int")]
    #[Groups(array("read", "write"))]
    #[ORM\Column(type: Types::INTEGER, options: array("default" => 0))]
    public int $totalPriceCents = 0;

    #[Assert\NotNull]
    #[Assert\Type("int")]
    #[Groups(array("read", "write"))]
    #[ORM\Column(type: Types::INTEGER, options: array("default" => 0))]
    public int $totalWithoutTaxCents = 0;

    #[Assert\NotNull]
    #[Assert\Type("int")]
    #[Groups(array("read", "write"))]
    #[ORM\Column(type: Types::INTEGER, options: array("default" => 0))]
    public int $totalTaxCents = 0;

    #[Assert\NotNull]
    #[Assert\Type("string")]
    #[Groups(array("read", "write"))]
    #[ORM\Column(type: Types::STRING, options: array("default" => "EUR"))]
    public string $totalPriceCurrency = "EUR";

    //====================================================================//
    // SHIPPING TOTALS
    //====================================================================//

    #[Assert\NotNull]
    #[Assert\Type("int")]
    #[Groups(array("read", "write"))]
    #[ORM\Column(type: Types::INTEGER, options: array("default" => 0))]
    public int $totalShippingCents = 0;

    #[Assert\NotNull]
    #[Assert\Type("int")]
    #[Groups(array("read", "write"))]
    #[ORM\Column(type: Types::INTEGER, options: array("default" => 0))]
    public int $totalShippingTaxIncludedCents = 0;

    #[Assert\NotNull]
    #[Assert\Type("int")]
    #[Groups(array("read", "write"))]
    #[ORM\Column(type: Types::INTEGER, options: array("default" => 0))]
    public int $totalShippingTaxCents = 0;

    #[Assert\NotNull]
    #[Assert\Type("string")]
    #[Groups(array("read", "write"))]
    #[ORM\Column(type: Types::STRING, options: array("default" => "EUR"))]
    public string $totalShippingTaxIncludedCurrency = "EUR";

    //====================================================================//
    // DISCOUNT TOTALS
    //====================================================================//

    #[Assert\NotNull]
    #[Assert\Type("int")]
    #[Groups(array("read", "write"))]
    #[ORM\Column(type: Types::INTEGER, options: array("default" => 0))]
    public int $totalDiscountTaxIncludedCents = 0;

    #[Assert\NotNull]
    #[Assert\Type("int")]
    #[Groups(array("read", "write"))]
    #[ORM\Column(type: Types::INTEGER, options: array("default" => 0))]
    public int $totalDiscountCents = 0;

    #[Assert\NotNull]
    #[Assert\Type("string")]
    #[Groups(array("read", "write"))]
    #[ORM\Column(type: Types::STRING, options: array("default" => "EUR"))]
    public string $totalDiscountTaxIncludedCurrency = "EUR";

    //====================================================================//
    // WEIGHT TOTALS
    //====================================================================//

    #[Assert\NotNull]
    #[Assert\Type("int")]
    #[Groups(array("read"))]
    #[ORM\Column(type: Types::INTEGER, options: array("default" => 0))]
    public int $totalWeight = 0;
}
