<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 05/05/18
 * Time: 02:44
 */

namespace TiendaNube\Checkout\Service\Address;


class PdoStrategy implements AddressStrategyInterface
{

    /**
     * The database connection link
     *
     * @var \PDO
     */
    private $connection;

    /**
     * PdoStrategy constructor.
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->connection = $pdo;
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
     * @return array
     */
    public function getAddressByZip(string $zip):?array
    {
        // getting the address from database
        $stmt = $this->connection->prepare('SELECT * FROM `addresses` WHERE `zipcode` = ?');
        $stmt->execute([$zip]);

        // checking if the address exists
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        }

        return null;
    }
}