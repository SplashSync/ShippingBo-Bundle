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

namespace Splash\Connectors\ShippingBo\Models\Api;

use JMS\Serializer\Annotation as JMS;
use Splash\OpenApi\Validator as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing the Address model.
 *
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class Address
{
    use Core\SboCoreTrait;

    const COLLECTION_PROP = "addresses";
    const ITEMS_PROP = "address";

    /**
     * Unique identifier.
     *
     * @var string
     * @Assert\NotNull()
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("id")
     * @JMS\Type("string")
     * @JMS\Groups ({"Read", "List"})
     */
    public string $id;

    /**
     * Client's full name.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("fullname")
     * @JMS\Type("string")
     * @JMS\Groups ({"Read", "Write"})
     *
     * @SPL\Microdata({"http://schema.org/PostalAddress", "alternateName"})
     */
    public ?string $fullname = null;

    /**
     * Client's firstname.
     *
     * @var null|string
     * @Assert\NotNull()
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("firstname")
     * @JMS\Type("string")
     * @JMS\Groups ({"Read", "Write", "List"})
     *
     * @SPL\Microdata({"http://schema.org/Person", "familyName"})
     */
    public ?string $firstname = null;

    /**
     * Client's lastname.
     *
     * @var string
     * @Assert\NotNull()
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("lastname")
     * @JMS\Type("string")
     * @JMS\Groups ({"Read", "Write", "List"})
     *
     * @SPL\Microdata({"http://schema.org/Person", "givenName"})
     */
    public ?string $lastname = null;

    /**
     * Client's Company Name.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("company_name")
     * @JMS\Type("string")
     * @JMS\Groups ({"Read", "Write"})
     *
     * @SPL\Microdata({"http://schema.org/Organization", "legalName"})
     */
    public ?string $company = null;

    /**
     * Client's Email.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("email")
     * @JMS\Type("string")
     * @JMS\Groups ({"Read", "Write", "List"})
     *
     * @SPL\Microdata({"http://schema.org/ContactPoint", "email"})
     * @SPL\Type ("email")
     */
    public ?string $email = null;

    /**
     * Client's Phone 1.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("phone1")
     * @JMS\Type("string")
     * @JMS\Groups({"Read", "Write"})
     *
     * @SPL\Microdata({"http://schema.org/PostalAddress", "telephone"})
     * @SPL\Type("phone")
     */
    public ?string $phone1 = null;

    /**
     * Client's Phone 2.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("phone2")
     * @JMS\Type("string")
     * @JMS\Groups({"Read", "Write"})
     *
     * @SPL\Microdata({"http://schema.org/Person", "telephone"})
     * @SPL\Type("phone")
     */
    public ?string $phone2 = null;

    /**
     * Client's Street 1.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("street1")
     * @JMS\Type("string")
     * @JMS\Groups ({"Read", "Write"})
     *
     * @SPL\Microdata({"http://schema.org/PostalAddress", "streetAddress"})
     */
    public ?string $street1 = null;

    /**
     * Client's Street 2.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("street2")
     * @JMS\Type("string")
     * @JMS\Groups ({"Read", "Write"})
     *
     * @SPL\Microdata({"http://schema.org/PostalAddress", "postOfficeBoxNumber"})
     */
    public ?string $street2 = null;

    /**
     * Client's Street 3.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("street3")
     * @JMS\Type("string")
     * @JMS\Groups ({"Read", "Write"})
     */
    public ?string $street3 = null;

    /**
     * Client's Street 4.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("street4")
     * @JMS\Type("string")
     * @JMS\Groups ({"Read", "Write"})
     */
    public ?string $street4 = null;

    /**
     * Client's City.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("city")
     * @JMS\Type("string")
     * @JMS\Groups ({"Read", "Write", "List"})
     *
     * @SPL\Microdata({"http://schema.org/PostalAddress", "addressLocality"})
     */
    public ?string $city = null;

    /**
     * Client's Zip.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("zip")
     * @JMS\Type("string")
     * @JMS\Groups ({"Read", "Write"})
     *
     * @SPL\Microdata({"http://schema.org/PostalAddress", "postalCode"})
     */
    public ?string $zip = null;

    /**
     * Client's State.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("state")
     * @JMS\Type("string")
     * @JMS\Groups ({"Read", "Write"})
     *
     * @SPL\Microdata({"http://schema.org/PostalAddress", "addressRegion"})
     */
    public ?string $state = null;

    /**
     * Client's Country.
     *
     * @var string
     * @Assert\NotNull()
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("country")
     * @JMS\Type("string")
     * @JMS\Groups ({"Read", "Write", "Required"})
     *
     * @SPL\Microdata({"http://schema.org/PostalAddress", "addressCountry"})
     * @SPL\Type ("country")
     */
    public string $country;

    /**
     * Client's Building.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("building")
     * @JMS\Type("string")
     * @JMS\Groups ({"Read", "Write"})
     */
    public ?string $building = null;

    /**
     * Client's Apartment Number.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("apartment_number")
     * @JMS\Type("string")
     * @JMS\Groups ({"Read", "Write"})
     */
    public ?string $apartment = null;

    /**
     * Client's Instruction.
     *
     * @var null|string
     * @Assert\Type("string")
     *
     * @JMS\SerializedName("instructions")
     * @JMS\Type("string")
     * @JMS\Groups ({"Read", "Write"})
     */
    public ?string $instructions = null;
}
