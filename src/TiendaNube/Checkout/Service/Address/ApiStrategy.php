<?php
/**
 * Created by PhpStorm.
 * User: leo
 * Date: 05/05/18
 * Time: 02:52
 */

namespace TiendaNube\Checkout\Service\Address;



use GuzzleHttp\Exception\GuzzleException;

class ApiStrategy implements AddressStrategyInterface
{
    const BASE_URL = "https://shipping.tiendanube.com/v1/";
    const ADDRESS_ENDPOINT = "address/";
    /** @var \GuzzleHttp\Client */
    private $client;

    /**
     * ApiStrategy constructor.
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(\GuzzleHttp\Client $client)
    {
        $this->client = $client;
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
     * @throws GuzzleException | \Exception
     */
    public function getAddressByZip(string $zip):?array
    {
        $result = null;
        $response = $this->client->request('GET', $this->buildUrl($zip));
        $code = $response->getStatusCode();
        switch ($code) {
            case 200:
                $result = json_decode((string)$response->getBody(), true);
                break;
            case 404:
                $result = null;
                break;
            case 500:
                throw new \Exception('Internal server error');
        }
        return $result;
    }

    /**
     * @param string $zip
     * @return string
     */
    private function buildUrl(string $zip): string
    {
        return self::BASE_URL . self::ADDRESS_ENDPOINT . $zip;
    }
}