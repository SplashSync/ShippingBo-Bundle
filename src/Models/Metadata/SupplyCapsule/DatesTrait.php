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

namespace Splash\Connectors\ShippingBo\Models\Metadata\SupplyCapsule;

use DateTime;
use JMS\Serializer\Annotation as JMS;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

trait DatesTrait
{
    /**
     * The Expected Arrival Date of the goods.
     *
     * Exposed as a Splash date, as the standard ArrivalDate template: the
     * time part sent by ShippingBo is dropped on read and set to midnight
     * on write.
     */
    #[
        Assert\Type("DateTime"),
        JMS\SerializedName("expected_delivery_date"),
        JMS\Groups(array("Read", "Write")),
        JMS\Type("DateTime<'Y-m-d\\TH:i:s.v\\Z','',['Y-m-d\\TH:i:s.v\\Z', 'Y-m-d H:i:s']>"),
        SPL\Microdata("https://schema.org/ParcelDelivery", "expectedArrivalFrom"),
        SPL\Field(
            type: SPL_T_DATE,
            name: "Expected At",
            desc: "Expected arrival date of the goods.",
        )
    ]
    public ?DateTime $expectedDeliveryDate = null;
}
