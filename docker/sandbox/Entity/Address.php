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
use Doctrine\ORM\Mapping as ORM;
use Faker\Factory;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing the Address model.
 *
 * @ORM\Entity
 *
 * @ORM\HasLifecycleCallbacks()
 */
#[ApiResource()]
class Address implements SboObjectInterface
{
    use Core\SboCoreTrait;

    /**
     * Client's full name.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(nullable=true)
     */
    public ?string $fullname = null;

    /**
     * Client's firstname.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(nullable=true)
     */
    public ?string $firstname = null;

    /**
     * Client's lastname.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(nullable=true)
     */
    public ?string $lastname = null;

    /**
     * Client's Company Name.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(nullable=true)
     */
    public ?string $company_name = null;

    /**
     * Client's Email.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(nullable=true)
     */
    public ?string $email = null;

    /**
     * Client's phone 1.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(nullable=true)
     */
    public ?string $phone1 = null;

    /**
     * Client's phone 2.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(nullable=true)
     */
    public ?string $phone2 = null;

    /**
     * Client's street 1.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(nullable=true)
     */
    public ?string $street1 = null;

    /**
     * Client's street 2.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(nullable=true)
     */
    public ?string $street2 = null;

    /**
     * Client's street 3.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(nullable=true)
     */
    public ?string $street3 = null;

    /**
     * Client's street 4.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(nullable=true)
     */
    public ?string $street4 = null;

    /**
     * Client's city.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(nullable=true)
     */
    public ?string $city = null;

    /**
     * Client's zip.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(nullable=true)
     */
    public ?string $zip = null;

    /**
     * Client's state.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(nullable=true)
     */
    public ?string $state = null;

    /**
     * Client's country.
     *
     * @var string
     *
     * @Assert\NotNull()
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column
     */
    public string $country;

    /**
     * Client's Building.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(nullable=true)
     */
    public ?string $building = null;

    /**
     * Client's Apartment Number.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(nullable=true)
     */
    public ?string $apartment_number = null;

    /**
     * Client's Instruction.
     *
     * @var null|string
     *
     * @Assert\Type("string")
     *
     * @Groups({"read"})
     *
     * @ORM\Column(nullable=true)
     */
    public ?string $instructions = null;

    //====================================================================//
    // DATA FAKER
    //====================================================================//

    /**
     * Address Faker
     *
     * @return Address
     */
    public static function fake(): self
    {
        $faker = Factory::create("fr_FR");

        $address = new self();
        $address->firstname = $faker->firstName;
        $address->lastname = $faker->lastName;
        $address->company_name = $faker->company;
        $address->email = $faker->companyEmail;
        $address->phone1 = $faker->e164PhoneNumber;
        $address->phone2 = $faker->e164PhoneNumber;
        $address->street1 = $faker->streetAddress;
        $address->street2 = $faker->streetSuffix;
        $address->zip = $faker->postcode;
        $address->city = $faker->city;
        $address->country = $faker->countryISOAlpha3;

        return $address;
    }

    //====================================================================//
    // JSON SERIALIZER
    //====================================================================//

    /**
     * @inheritDoc
     */
    public static function getItemIndex(): ?string
    {
        return "address";
    }

    /**
     * @inheritDoc
     */
    public static function getCollectionIndex(): ?string
    {
        return "addresses";
    }
}
