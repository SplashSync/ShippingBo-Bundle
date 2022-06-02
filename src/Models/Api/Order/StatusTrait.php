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

namespace Splash\Connectors\ShippingBo\Models\Api\Order;

use JMS\Serializer\Annotation as JMS;
use Splash\OpenApi\Validator as SPL;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ShippingBo Order Status Model
 */
trait StatusTrait
{
    /**
     * Order Raw Status.
     *
     * @var string
     *
     * @Assert\NotNull()
     * @Assert\Type("string")
     * @Assert\Choice({
     *     "in_trouble":                "En erreur",
     *     "waiting_for_payment":       "Draft | En attente de paiement",
     *     "waiting_for_stock":         "En attente de stock",
     *     "merged":                    "Fusionnée",
     *     "sent_to_logistics":         "Envoyée en logistique",
     *     "dispatched":                "Aiguillée",
     *     "splitted":                  "Découpée",
     *     "to_be_prepared":            "A préparer",
     *     "in_preparation":            "En préparation",
     *     "partially_shipped":         "Expédiée partiellement",
     *     "shipped":                   "Expédiée",
     *     "handed_to_carrier":         "Remis au transporteur",
     *     "at_pickup_location":        "En point retrait",
     *     "closed":                    "Livrée",
     *     "back_from_client":          "Annulée après expé. | Retour",
     *     "rejected":                  "Non Livrée",
     *     "canceled":                  "Annulée",
     * })
     *
     * @JMS\SerializedName("state")
     * @JMS\Type("string")
     * @JMS\Groups ({"Read", "Write", "List", "Required"})
     *
     * @SPL\Prefer("none")
     * @SPL\Logged
     */
    public string $state;
}
