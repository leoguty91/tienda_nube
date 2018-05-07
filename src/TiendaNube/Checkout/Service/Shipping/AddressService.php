<?php

declare(strict_types=1);

namespace TiendaNube\Checkout\Service\Shipping;

use GuzzleHttp\Exception\TransferException;
use Psr\Log\LoggerInterface;
use TiendaNube\Checkout\Service\Address\AddressStrategyInterface;
use TiendaNube\Checkout\Service\Address\ApiStrategy;
use TiendaNube\Checkout\Service\Address\PdoStrategy;
use TiendaNube\Checkout\Service\Store\StoreSingleton;

/**
 * Class AddressService
 *
 * @package TiendaNube\Checkout\Service\Shipping
 */
class AddressService
{
    /**
     * The database connection link
     *
     * @var \PDO
     */
    private $connection;

    private $logger;
    private $clientHttp;
    /**
     * @var AddressStrategyInterface
     */
    private $addressStrategy;

    /**
     * AddressService constructor.
     *
     * @param \PDO $pdo
     * @param LoggerInterface $logger
     */
    public function __construct(\PDO $pdo, LoggerInterface $logger)
    {
        $this->connection = $pdo;
        $this->logger = $logger;
        $this->clientHttp = new \GuzzleHttp\Client();
    }

    /**
     * Get an address by its zipcode (CEP)
     *
     * The expected return format is an array like:
     * [
     *      "address" => "Avenida da França",
     *      "neighborhood" => "Comércio",
     *      "city" => "Salvador",
     *      "state" => "BA"
     * ]
     * or false when not found.
     *
     * @param string $zip
     * @return bool|array
     */
    public function getAddressByZip(string $zip): ?array
    {
        $this->logger->debug('Getting address for the zipcode [' . $zip . '] from database');

        try {
            $this->configureStrategy();

            return $this->addressStrategy->getAddressByZip($zip);
        } catch (\PDOException | TransferException $ex) {
            $this->logger->error(
                'An error occurred at try to fetch the address from the database, exception with message was caught: ' .
                $ex->getMessage()
            );

            return null;
        }
    }

    /**
     * Configure the strategy to use
     */
    private function configureStrategy()
    {
        if (StoreSingleton::instance()->getCurrentStore()->isBetaTester()) {
            $this->addressStrategy = new ApiStrategy($this->clientHttp);
        } else {
            $this->addressStrategy = new PdoStrategy($this->connection);
        }
    }

    /**
     * @param mixed $clientHttp
     */
    public function setClientHttp($clientHttp): void
    {
        $this->clientHttp = $clientHttp;
    }

}
