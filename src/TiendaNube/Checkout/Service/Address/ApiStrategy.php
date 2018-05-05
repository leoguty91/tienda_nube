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
    const BASE_URL = "https://shipping.tiendanube.com/v1/";
    const ADDRESS_ENDPOINT = "address/";

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
     * @throws \Httpful\Exception\ConnectionErrorException
     */
    public function getAddressByZip(string $zip):?array
    {
        $response = \Httpful\Request::get(self::BASE_URL . self::ADDRESS_ENDPOINT . $zip)->authenticateWith('bearer', 'YouShallNotPass')->send();
        // Codigo de estado http: $response->code;
        return $response->body;
    }
}