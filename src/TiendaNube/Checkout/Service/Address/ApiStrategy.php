<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 05/05/18
 * Time: 02:52
 */

namespace TiendaNube\Checkout\Service\Address;


class ApiStrategy implements AddressStrategyInterface
{

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
        // TODO: Implement getAddressByZip() method.
        return null;
    }
}