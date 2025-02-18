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
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Faker\Factory;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing the Address model.
 */
#[ApiResource]
#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Address implements SboObjectInterface
{
    use Core\SboCoreTrait;

    /**
     * Client's full name.
     */
    #[Assert\Type(Types::STRING)]
    #[Groups(array('read'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $fullname = null;

    /**
     * Client's firstname.
     */
    #[Assert\Type(Types::STRING)]
    #[Groups(array('read'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $firstname = null;

    /**
     * Client's lastname.
     */
    #[Assert\Type(Types::STRING)]
    #[Groups(array('read'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $lastname = null;

    /**
     * Client's Company Name.
     */
    #[Assert\Type(Types::STRING)]
    #[Groups(array('read'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $companyName = null;

    /**
     * Client's Email.
     */
    #[Assert\Type(Types::STRING)]
    #[Groups(array('read'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $email = null;

    /**
     * Client's phone 1.
     */
    #[Assert\Type(Types::STRING)]
    #[Groups(array('read'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $phone1 = null;

    /**
     * Client's phone 2.
     */
    #[Assert\Type(Types::STRING)]
    #[Groups(array('read'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $phone2 = null;

    /**
     * Client's street 1.
     */
    #[Assert\Type(Types::STRING)]
    #[Groups(array('read'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $street1 = null;

    /**
     * Client's street 2.
     */
    #[Assert\Type(Types::STRING)]
    #[Groups(array('read'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $street2 = null;

    /**
     * Client's street 3.
     */
    #[Assert\Type(Types::STRING)]
    #[Groups(array('read'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $street3 = null;

    /**
     * Client's street 4.
     */
    #[Assert\Type(Types::STRING)]
    #[Groups(array('read'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $street4 = null;

    /**
     * Client's city.
     */
    #[Assert\Type(Types::STRING)]
    #[Groups(array('read'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $city = null;

    /**
     * Client's zip.
     */
    #[Assert\Type(Types::STRING)]
    #[Groups(array('read'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $zip = null;

    /**
     * Client's state.
     */
    #[Assert\Type(Types::STRING)]
    #[Groups(array('read'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $state = null;

    /**
     * Client's country.
     */
    #[Assert\NotNull]
    #[Assert\Type(Types::STRING)]
    #[Groups(array('read'))]
    #[ORM\Column(type: Types::STRING)]
    public string $country;

    /**
     * Client's Building.
     */
    #[Assert\Type(Types::STRING)]
    #[Groups(array('read'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $building = null;

    /**
     * Client's Apartment Number.
     */
    #[Assert\Type(Types::STRING)]
    #[Groups(array('read'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
    public ?string $apartmentNumber = null;

    /**
     * Client's Instruction.
     */
    #[Assert\Type(Types::STRING)]
    #[Groups(array('read'))]
    #[ORM\Column(type: Types::STRING, nullable: true)]
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
        $address->companyName = $faker->company;
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
