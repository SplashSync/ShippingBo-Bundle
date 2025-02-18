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

namespace Splash\Connectors\ShippingBo\Services;

use ArrayObject;
use Exception;
use Httpful\Mime;
use Psr\Log\LoggerInterface;
use Splash\Bundle\Interfaces\Connectors\PrimaryKeysInterface;
use Splash\Bundle\Interfaces\Connectors\TrackingInterface;
use Splash\Bundle\Models\AbstractConnector;
use Splash\Bundle\Models\Connectors\GenericObjectMapperTrait;
use Splash\Bundle\Models\Connectors\GenericObjectPrimaryMapperTrait;
use Splash\Bundle\Models\Connectors\GenericWidgetMapperTrait;
use Splash\Connectors\ShippingBo\Form\EditFormType;
use Splash\Connectors\ShippingBo\Hydrator\Hydrator;
use Splash\Connectors\ShippingBo\Models\Connector\ConfigurationTrait;
use Splash\Connectors\ShippingBo\Objects;
use Splash\Connectors\ShippingBo\Widgets;
use Splash\Core\SplashCore as Splash;
use Splash\Metadata\Services\MetadataAdapter;
use Splash\OpenApi\Action;
use Splash\OpenApi\Connexion\JsonConnexion;
use Splash\OpenApi\Models\Connexion\ConnexionInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * ShippingBo REST API Connector for Splash
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ShippingBoConnector extends AbstractConnector implements TrackingInterface, PrimaryKeysInterface
{
    use GenericObjectMapperTrait;
    use GenericObjectPrimaryMapperTrait;
    use GenericWidgetMapperTrait;
    use ConfigurationTrait;

    /**
     * Objects Type Class Map
     *
     * @var array<string, class-string>
     */
    protected static array $objectsMap = array(
        "Order" => Objects\Order::class,
        "Product" => Objects\Product::class,
        "Address" => Objects\Address::class,
        "SupplierOrder" => Objects\SupplierOrder::class,
        "Webhook" => Objects\Webhook::class,
    );

    /**
     * Widgets Type Class Map
     *
     * @var array<string, class-string>
     */
    protected static array $widgetsMap = array(
        "SelfTest" => Widgets\SelfTest::class,
    );

    /**
     * @var ConnexionInterface
     */
    private ConnexionInterface $connexion;

    /**
     * Object Hydrator
     *
     * @var Hydrator
     */
    private Hydrator $hydrator;

    /**
     * @var string
     */
    private string $metaDir;

    public function __construct(
        private readonly MetadataAdapter   $metadataAdapter,
        private ShippingBoLocator $locator,
        string $metaDir,
        EventDispatcherInterface $eventDispatcher,
        LoggerInterface $logger
    ) {
        parent::__construct($eventDispatcher, $logger);
        $this->metaDir = $metaDir."/metadata/shippingbo";
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function ping() : bool
    {
        //====================================================================//
        // Safety Check => Verify Self-test Pass
        if (!$this->selfTest()) {
            return false;
        }

        //====================================================================//
        // Perform Ping Test
        return Action\Ping::execute($this->getConnexion(), "/products");
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function connect() : bool
    {
        //====================================================================//
        // Safety Check => Verify Self-test Pass
        if (!$this->selfTest()) {
            return false;
        }
        //====================================================================//
        // Get User Information
        if (!$this->fetchUserInformation()) {
            return false;
        }
        //====================================================================//
        // Get Available Shipping Methods
        if (!$this->fetchShippingMethods()) {
            return false;
        }
        //====================================================================//
        // Get Available Shipping Methods
        if (!$this->fetchLogisticServices()) {
            return false;
        }
        //====================================================================//
        // Update Connector Settings
        $this->updateConfiguration();

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function informations(ArrayObject  $informations) : ArrayObject
    {
        //====================================================================//
        // Server General Description
        $informations->shortdesc = "ShippingBo API";
        $informations->longdesc = "Splash Integration for ShippingBo OpenApi V1";
        //====================================================================//
        // Company Information
        $informations->company = "ShippingBo";
        $informations->address = "3 Av. de l'Europe Bat D";
        $informations->zip = "31400";
        $informations->town = "Toulouse";
        $informations->country = "France";
        $informations->www = "https://shippingbo.com/";
        $informations->email = "contact@shippingbo.com";
        $informations->phone = "+33 (0) 5 34 41 23 43";
        //====================================================================//
        // Server Logo & Ico
        $informations->icoraw = Splash::file()->readFileContents(
            dirname(__FILE__, 2)."/Resources/public/img/ShippingBo-Icon.jpg"
        );
        $informations->logourl = null;
        $informations->logoraw = Splash::file()->readFileContents(
            dirname(__FILE__, 2)."/Resources/public/img/ShippingBo-Logo.jpg"
        );
        //====================================================================//
        // Server Information
        $informations->servertype = "ShippingBo Api V1";
        $informations->serverurl = "shippingbo.com";
        //====================================================================//
        // Module Information
        $informations->moduleauthor = "Splash Official <www.splashsync.com>";
        $informations->moduleversion = "master";

        return $informations;
    }

    /**
     * {@inheritdoc}
     */
    public function selfTest() : bool
    {
        $config = $this->getConfiguration();

        //====================================================================//
        // Verify Api User is Set
        //====================================================================//
        if (empty($config["ApiUser"]) || !is_string($config["ApiUser"])) {
            Splash::log()->err("Api User is Invalid");

            return false;
        }

        //====================================================================//
        // Verify Api Key is Set
        //====================================================================//
        if (empty($config["ApiKey"]) || !is_string($config["ApiKey"])) {
            Splash::log()->err("Api Key is Invalid");

            return false;
        }

        //====================================================================//
        // Create or Refresh Connexion
        //====================================================================//
        $this->getConnexion();

        return true;
    }

    //====================================================================//
    // Files Interfaces
    //====================================================================//

    /**
     * {@inheritdoc}
     *
     * @throws Exception
     */
    public function getFile(string $filePath, string $fileMd5): ?array
    {
        //====================================================================//
        // Safety Check => Verify Self-test Pass
        if (!$this->selfTest()) {
            return null;
        }
        //====================================================================//
        // Read File Contents via Raw Get Request
        $rawResponse = $this->getConnexion()->getRaw($filePath);
        if (!$rawResponse || (md5($rawResponse) != $fileMd5)) {
            return null;
        }
        //====================================================================//
        // Build File Array
        $file = array();
        $file["name"] = $file["filename"] = pathinfo($filePath, PATHINFO_BASENAME);
        $file["path"] = $filePath;
        $file["url"] = $filePath;
        $file["raw"] = base64_encode((string) $rawResponse);
        $file["md5"] = md5($rawResponse);
        $file["size"] = strlen($rawResponse);

        return $file;
    }

    //====================================================================//
    // ReCommerce Connector Specific
    //====================================================================//

    /**
     * Check if Connector use Sandbox Mode
     *
     * @return bool
     */
    public function isSandbox(): bool
    {
        if ($this->getParameter("isSandbox", false)) {
            return true;
        }

        return false;
    }

    //====================================================================//
    // Open API Connector Interfaces
    //====================================================================//

    /**
     * Get Connector Api Connexion
     *
     * @return ConnexionInterface
     */
    public function getConnexion() : ConnexionInterface
    {
        //====================================================================//
        // Get Configuration
        $config = $this->getConfiguration();
        //====================================================================//
        // Connexion already created
        if (isset($this->connexion)) {
            //====================================================================//
            // Connexion Unchanged
            if ($this->connexion->getTemplate()->headers['X-API-USER'] == $config["ApiUser"]) {
                return $this->connexion;
            }
        }
        //====================================================================//
        // Detect Api Url
        $url = $this->getParameter("isSandbox", false)
            ? $config["WsHost"]
            : "https://app.shippingbo.com"
        ;
        //====================================================================//
        // Setup Api Connexion
        $this->connexion = new JsonConnexion($url, array(
            'X-API-USER' => $config["ApiUser"],
            'X-API-TOKEN' => $config["ApiKey"],
            'X-API-VERSION' => 1,
        ));
        if (!$this->isSandbox()) {
            $this->connexion->setPatchMimeType(Mime::JSON);
        }

        return $this->connexion;
    }

    /**
     * @return Hydrator
     */
    public function getHydrator(): Hydrator
    {
        //====================================================================//
        // Configure Object Hydrator
        if (!isset($this->hydrator)) {
            $this->hydrator = new Hydrator($this->metaDir);
        }

        return $this->hydrator;
    }

    /**
     * Get Splash Metadata Adapter
     */
    public function getMetadataAdapter(): MetadataAdapter
    {
        return $this->metadataAdapter;
    }

    /**
     * Get Services Locator
     */
    public function getLocator(): ShippingBoLocator
    {
        return $this->locator->configure($this);
    }

    /**
     * Get ShippingBo User Information
     *
     * @throws Exception
     *
     * @return bool
     */
    private function fetchUserInformation(): bool
    {
        //====================================================================//
        // Get User Infos from Api
        $response = $this->getConnexion()->get("/users/me");
        if (!isset($response["user"]) || !is_array($response["user"])) {
            return false;
        }
        //====================================================================//
        // Store in Connector Settings
        $this->setParameter("UserInformations", $response["user"]);

        return true;
    }

    /**
     * Get List of Configured Shipping Methods
     *
     * @throws Exception
     *
     * @return bool
     */
    private function fetchShippingMethods(): bool
    {
        //====================================================================//
        // Get Shipping Methods from Api
        $response = $this->getConnexion()->get("/shipping_methods");
        if (!isset($response["shipping_methods"]) || !is_array($response["shipping_methods"])) {
            return false;
        }
        //====================================================================//
        // Store in Connector Settings
        $this->setParameter("ShippingMethodsList", $response["shipping_methods"]);

        return true;
    }

    /**
     * Get ShippingBo Logistic Services Configuration
     *
     * @return bool
     */
    private function fetchLogisticServices(): bool
    {
        //====================================================================//
        // Store in Connector Settings
        $this->setParameter(
            "ShippingMethodChoices",
            EditFormType::getStaticShippingMethodsChoices()
        );

        return true;
    }
}
