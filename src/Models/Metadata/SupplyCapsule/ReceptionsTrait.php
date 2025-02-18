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

use JMS\Serializer\Annotation as JMS;
use Splash\Connectors\ShippingBo\Models\Metadata\SupplyCapsuleReception;
use Splash\Metadata\Attributes as SPL;
use Symfony\Component\Validator\Constraints as Assert;

trait ReceptionsTrait
{
    /**
     * Supplier Order Receptions Lines.
     *
     * @var SupplyCapsuleReception[]
     */
    #[
        Assert\All(array(
            new Assert\Type(SupplyCapsuleReception::class)
        )),
        JMS\Exclude,
        SPL\ListResource(targetClass: SupplyCapsuleReception::class),
        SPL\IsReadOnly,
    ]
    protected array $receptions = array();

    /**
     * @return array
     */
    public function getReceptions(): array
    {
        $this->receptions = array();

        foreach ($this->oldSupplyCapsuleItems as $capsuleItem) {
            if (!empty($capsuleItem->receptions)) {
                $this->receptions[] = new SupplyCapsuleReception($capsuleItem);
            }
        }

        return $this->receptions;
    }
}
