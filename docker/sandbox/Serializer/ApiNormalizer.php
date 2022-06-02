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

namespace App\Serializer;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use App\Entity\SboObjectInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class ApiNormalizer implements NormalizerInterface, DenormalizerInterface, SerializerAwareInterface
{
    public const FORMAT = 'json';

    /**
     * @var DenormalizerInterface|NormalizerInterface
     */
    private $decorated;

    /**
     * @param NormalizerInterface $decorated
     */
    public function __construct(NormalizerInterface $decorated)
    {
        if (!$decorated instanceof DenormalizerInterface) {
            throw new \InvalidArgumentException(sprintf('The decorated normalizer must implement the %s.', DenormalizerInterface::class));
        }

        $this->decorated = $decorated;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsNormalization($data, $format = null): bool
    {
        if ($this->decorated->supportsNormalization($data, $format)) {
            return true;
        }
        if ((static::FORMAT === $format) && ($data instanceof Paginator)) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function normalize($object, $format = null, array $context = array())
    {
        //====================================================================//
        //  Check if Ressource is Sbo Ressource
        if (!self::isManagedObject($context["resource_class"])) {
            return $object;
        }
        //====================================================================//
        //  Collection Normalizer
        if ($object instanceof Paginator) {
            $data = array();
            $parent = $context["resource_class"];
            /** @var SboObjectInterface $obj */
            foreach ($object as $index => $obj) {
                $parent = $obj::getCollectionIndex();
                $data[$index] = $this->decorated->normalize($obj, $format, $context);
            }

            return $parent ? array($parent => $data, 'total' => $object->getTotalItems()) : $data;
        }
        //====================================================================//
        //  Item Normalizer
        if ("item" == $context["operation_type"]) {
            /** @var SboObjectInterface $object */
            return ($object::getItemIndex() && !isset($context["api_attribute"]))
                ? array($object::getItemIndex() => $this->decorated->normalize($object, $format, $context))
                : $this->decorated->normalize($object, $format, $context);
        }

        //====================================================================//
        //  Collection Normalizer
        if ("collection" == $context["operation_type"]) {
            return $this->decorated->normalize($object, $format, $context);
        }

        return $object;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return $this->decorated->supportsDenormalization($data, $type, $format);
    }

    /**
     * {@inheritDoc}
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        //====================================================================//
        //  Check if Ressource is Sbo Ressource
        if (!self::isManagedObject($class)) {
            return $data;
        }

        return $this->decorated->denormalize($data, $class, $format, $context);
    }

    /**
     * {@inheritDoc}
     */
    public function setSerializer(SerializerInterface $serializer)
    {
        if ($this->decorated instanceof SerializerAwareInterface) {
            $this->decorated->setSerializer($serializer);
        }
    }

    /**
     * Check if Object is managed by Sbo Serializer
     *
     * @param class-string $className
     *
     * @return bool
     */
    private static function isManagedObject(string $className): bool
    {
        return (class_exists($className) && is_subclass_of($className, SboObjectInterface::class));
    }
}
