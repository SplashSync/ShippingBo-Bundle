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

namespace Splash\Connectors\ShippingBo\Form;

use Burgov\Bundle\KeyValueFormBundle\Form\Type\KeyValueType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Throwable;

/**
 * Base Form Type for ReCommerce Connectors Servers
 */
abstract class AbstractShippingBoType extends AbstractType
{
    /**
     * Get Default Static Shipping Methods Choices
     *
     * @return string[]
     */
    public static function getStaticShippingMethodsChoices(): array
    {
        return array(
            "var.carriers.default" => "default",
            "var.carriers.rejected" => "REJECTED",
        );
    }

    /**
     * Add Ws Host Url Field to FormBuilder
     *
     * @param FormBuilderInterface $builder
     *
     * @return $this
     */
    public function addWsHostField(FormBuilderInterface $builder): self
    {
        $builder
            //==============================================================================
            // Optilog Api Host Url
            ->add('WsHost', UrlType::class, array(
                'label' => "var.apiurl.label",
                'help' => "var.apiurl.desc",
                'required' => true,
                'translation_domain' => "ShippingBoBundle",
            ))
        ;

        return $this;
    }

    /**
     * Add Api User Field to FormBuilder
     *
     * @param FormBuilderInterface $builder
     *
     * @return $this
     */
    public function addApiUserField(FormBuilderInterface $builder): self
    {
        $builder
            //==============================================================================
            // Optilog Api Key For Authentification
            ->add('ApiUser', TextType::class, array(
                'label' => "var.apiuser.label",
                'help' => "var.apiuser.desc",
                'required' => true,
                'translation_domain' => "ShippingBoBundle",
            ))
        ;

        return $this;
    }

    /**
     * Add Api Key Field to FormBuilder
     *
     * @param FormBuilderInterface $builder
     *
     * @return $this
     */
    public function addApiKeyField(FormBuilderInterface $builder): self
    {
        $builder
            //==============================================================================
            // Optilog Api Key For Authentification
            ->add('ApiKey', TextType::class, array(
                'label' => "var.apikey.label",
                'help' => "var.apikey.desc",
                'required' => true,
                'translation_domain' => "ShippingBoBundle",
            ))
        ;

        return $this;
    }

    /**
     * Add Order Min Created Date & Time Filter to FormBuilder
     *
     * @param FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addMinObjectCreateDateField(FormBuilderInterface $builder): self
    {
        $builder
            ->add('minObjectDate', DateTimeType::class, array(
                'label' => "var.minObjectDate.label",
                'help' => "var.minObjectDate.desc",
                'widget' => 'single_text',
                'required' => false,
                'translation_domain' => "ShippingBoBundle",
            ))
        ;

        return $this;
    }

    /**
     * Add Shipping Methods Names Field to FormBuilder
     *
     * @param FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addShippingMethodsField(FormBuilderInterface $builder): self
    {
        try {
            $choices = $builder->getData()["ShippingMethodChoices"];
        } catch (Throwable $ex) {
            $choices = self::getStaticShippingMethodsChoices();
        }

        $builder
            ->add('ShippingMethods', KeyValueType::class, array(
                'label' => "var.carriers.label",
                'help' => "var.carriers.desc",
                'required' => false,
                'key_type' => TextType::class,
                'key_options' => array(
                    'label' => "Shipping Method Name",
                ),
                'value_type' => ChoiceType::class,
                'value_options' => array(
                    'label' => "Action",
                    'choices' => $choices,
                ),
                'translation_domain' => "ShippingBoBundle",
            ))
        ;

        return $this;
    }

    /**
     * Add Origin Filters Field to FormBuilder
     *
     * @param FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addOriginFilterField(FormBuilderInterface $builder): self
    {
        $choices = array(
            "var.origin.default" => "pass",
            "var.origin.rejected" => "REJECTED",
        );

        $builder
            ->add('OrderOrigins', KeyValueType::class, array(
                'label' => "var.origin.label",
                'help' => "var.origin.desc",
                'required' => false,
                'key_type' => TextType::class,
                'key_options' => array(
                    'label' => "Origin",
                ),
                'value_type' => ChoiceType::class,
                'value_options' => array(
                    'label' => "Action",
                    'choices' => $choices,
                ),
                'translation_domain' => "ShippingBoBundle",
            ))
        ;

        return $this;
    }
}
