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

namespace App\Entity\Order;

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

    /**
     * @var int
     *
     * @Assert\NotNull()
     * @Assert\Type("int")
     *
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    public int $total_price_cents = 0;

    /**
     * @var int
     *
     * @Assert\NotNull()
     * @Assert\Type("int")
     *
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    public int $total_without_tax_cents = 0;

    /**
     * @var int
     *
     * @Assert\NotNull()
     * @Assert\Type("int")
     *
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    public int $total_tax_cents = 0;

    /**
     * @var string
     *
     * @Assert\NotNull()
     * @Assert\Type("string")
     *
     * @Groups({"read", "write"})
     *
     * @ORM\Column(type="string", options={"default" : "EUR"})
     */
    public string $total_price_currency = "EUR";

    //====================================================================//
    // SHIPPING TOTALS
    //====================================================================//

    /**
     * @var int
     *
     * @Assert\NotNull()
     * @Assert\Type("int")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    public int $total_shipping_cents = 0;

    /**
     * @var int
     *
     * @Assert\NotNull()
     * @Assert\Type("int")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    public int $total_shipping_tax_included_cents = 0;

    /**
     * @var int
     *
     * @Assert\NotNull()
     * @Assert\Type("int")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    public int $total_shipping_tax_cents = 0;

    /**
     * @var string
     *
     * @Assert\NotNull()
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", options={"default" : "EUR"})
     */
    public string $total_shipping_tax_included_currency = "EUR";

    //====================================================================//
    // DISCOUNT TOTALS
    //====================================================================//

    /**
     * @var int
     *
     * @Assert\NotNull()
     * @Assert\Type("int")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    public int $total_discount_tax_included_cents = 0;

    /**
     * @var int
     *
     * @Assert\NotNull()
     * @Assert\Type("int")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    public int $total_discount_cents = 0;

    /**
     * @var string
     *
     * @Assert\NotNull()
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="string", options={"default" : "EUR"})
     */
    public string $total_discount_tax_included_currency = "EUR";

    //====================================================================//
    // WEIGHT TOTALS
    //====================================================================//

    /**
     * @var int
     *
     * @Assert\NotNull()
     * @Assert\Type("int")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    public int $total_weight = 0;
}
