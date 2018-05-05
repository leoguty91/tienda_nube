<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 05/05/18
 * Time: 03:51
 */

namespace TiendaNube\Checkout\Service\Store;


use TiendaNube\Checkout\Model\Store;

class StoreSingleton implements StoreServiceInterface
{
    /** @var StoreSingleton */
    private static $instance;
    /** @var Store */
    private $store;

    /**
     * StoreSingleton constructor.
     */
    public function __construct()
    {
        $this->store = new Store();
    }

    /**
     * @return StoreSingleton
     */
    public static function instance()
    {
        if (!isset(self::$instance)) {
            $myClass = __CLASS__;
            self::$instance = new $myClass;
        }
        return self::$instance;
    }

    /**
     * Get the current store instance
     *
     * @return Store
     */
    public function getCurrentStore(): Store
    {
        return $this->store;
    }

    /**
     * @param Store $store
     */
    public function setStore(Store $store): void
    {
        $this->store = $store;
    }
}