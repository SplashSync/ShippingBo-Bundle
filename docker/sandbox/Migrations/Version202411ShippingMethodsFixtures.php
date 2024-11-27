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

namespace DoctrineMigrations;

// phpcs:disable Generic.Files.LineLength

use App\Entity\ShippingMethod;
use App\Entity\Taxe;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class Version202411ShippingMethodsFixtures extends AbstractMigration implements ContainerAwareInterface
{
    private ContainerInterface $container;
    public function getDescription(): string
    {
        return 'ShippingBo Sandbox: Populate Shipping Methods';
    }

    /**
     * Inject Container
     */
    public function setContainer(ContainerInterface $container = null): void
    {
        $this->container = $container;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.ShortMethodName)
     */
    public function up(Schema $schema): void
    {
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function postUp(Schema $schema): void
    {
        /** @var EntityManagerInterface $em */
        $em = $this->container->get('doctrine.orm.entity_manager');

        foreach ($this->getShippingMethods() as $shippingMethod) {
            if (!$em->contains($shippingMethod)) {
                $em->persist($shippingMethod);
            }
        }

        $em->flush();
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function down(Schema $schema): void
    {
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function postDown(Schema $schema): void
    {
        /** @var EntityManagerInterface $em */
        $em = $this->container->get('doctrine.orm.entity_manager');

        foreach ($em->getRepository(Taxe::class)->findAll() as $taxe) {
            $em->remove($taxe);
        }

        $em->flush();
    }

    /**
     * @return ShippingMethod[]
     */
    private function getShippingMethods(): array
    {
        $default = new ShippingMethod();
        $default->name = 'Collissimo';
        $default->carrier_id = 1;

        $gls = new ShippingMethod();
        $gls->name = 'GLS';
        $gls->carrier_id = 2;

        $chrono = new ShippingMethod();
        $chrono->name = 'Chronopost';
        $chrono->carrier_id = 3;

        return array($default, $gls, $chrono);
    }
}