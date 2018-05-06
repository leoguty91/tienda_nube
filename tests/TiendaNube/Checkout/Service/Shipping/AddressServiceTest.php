<?php

namespace TiendaNube\Checkout\Service\Shipping;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use TiendaNube\Checkout\Service\Store\StoreSingleton;

class AddressServiceTest extends TestCase
{
    protected $storeIsBetaTester;

    public function testGetExistentAddressByZipcode()
    {
        // expected address
        $address = [
            'address' => 'Avenida da França',
            'neighborhood' => 'Comércio',
            'city' => 'Salvador',
            'state' => 'BA',
        ];

        // mocking statement
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->method('rowCount')->willReturn(1);
        $stmt->method('fetch')->willReturn($address);

        // mocking pdo
        $pdo = $this->createMock(\PDO::class);
        $pdo->method('prepare')->willReturn($stmt);

        // mocking logger
        $logger = $this->createMock(LoggerInterface::class);

        // creating service
        $service = new AddressService($pdo,$logger);

        // testing
        $result = $service->getAddressByZip('40010000');

        // asserts
        $this->assertNotNull($result);
        $this->assertEquals($address,$result);
    }

    public function testGetNonexistentAddressByZipcode()
    {
        // mocking statement
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->method('rowCount')->willReturn(0);

        // mocking pdo
        $pdo = $this->createMock(\PDO::class);
        $pdo->method('prepare')->willReturn($stmt);

        // mocking logger
        $logger = $this->createMock(LoggerInterface::class);

        // creating service
        $service = new AddressService($pdo,$logger);

        // testing
        $result = $service->getAddressByZip('40010001');

        // asserts
        $this->assertNull($result);
    }

    public function testGetAddressByZipcodeWithPdoException()
    {
        // mocking pdo
        $pdo = $this->createMock(\PDO::class);
        $pdo->method('prepare')->willThrowException(new \PDOException('An error occurred'));

        // mocking logger
        $logger = $this->createMock(LoggerInterface::class);

        // creating service
        $service = new AddressService($pdo,$logger);

        // testing
        $result = $service->getAddressByZip('40010000');

        // asserts
        $this->assertNull($result);
    }

    public function testGetAddressByZipcodeWithUncaughtException()
    {
        // expects
        $this->expectException(\Exception::class);

        // mocking pdo
        $pdo = $this->createMock(\PDO::class);
        $pdo->method('prepare')->willThrowException(new \Exception('An error occurred'));

        // mocking logger
        $logger = $this->createMock(LoggerInterface::class);

        // creating service
        $service = new AddressService($pdo,$logger);

        // testing
        $service->getAddressByZip('40010000');
    }

    public function testGetExistentAddressByZipcodeBeta()
    {
        // expected address
        $address = [
            'altitude' => 7.0,
            'cep' => '40010000',
            'latitude' => '-12.967192',
            'longitude' => '-38.5101976',
            'address' => 'Avenida da França',
            'neighborhood' => 'Comércio',
            'city' => [
                'ddd' => 71,
                'ibge' => '2927408',
                'name' => 'Salvador'
            ],
            'state' => [
                'acronym' => 'BA'
            ],
        ];

        // mocking pdo
        $pdo = $this->createMock(\PDO::class);

        // mocking logger
        $logger = $this->createMock(LoggerInterface::class);

        // mocking client http and response
        $response = $this->createMock(\Psr\Http\Message\ResponseInterface::class);
        $response->method('getBody')->willReturn(json_encode($address));
        $clientHttp = $this->createMock(\GuzzleHttp\Client::class);
        $clientHttp->method('request')->willReturn($response);

        // store is beta
        StoreSingleton::instance()->getCurrentStore()->enableBetaTesting();

        // creating service
        $service = new AddressService($pdo,$logger);
        $service->setClientHttp($clientHttp);

        // testing
        $result = $service->getAddressByZip('40010000');

        // asserts
        $this->assertNotNull($result);
        $this->assertEquals($address,$result);
    }

}
