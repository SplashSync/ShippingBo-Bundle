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

namespace Splash\Connectors\ShippingBo\Form;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * ShippingBo Account Edit Form
 */
class EditFormType extends AbstractShippingBoType
{
    /**
     * Build Optilog Edit Form
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addApiUserField($builder);
        $this->addApiKeyField($builder);
        $this->addTimezoneField($builder);
        $this->addMinObjectCreateDateField($builder);
        $this->addDefaultShippingMethodField($builder);
        //        $this->addShippingMethodsField($builder);
        $this->addOriginFilterField($builder);
        $this->addItemsCountersField($builder);
        $this->addDefaultWarehouseSlotsField($builder);
        $this->addReadWriteWarehouseSlotsField($builder);
    }
}
