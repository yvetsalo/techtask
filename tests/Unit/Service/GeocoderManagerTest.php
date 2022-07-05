<?php

namespace App\Tests\Unit\Service;

use App\ValueObject\ResolvedAddress;
use App\Repository\ResolvedAddressRepository;
use App\Service\Geocoder\GeocoderInterface;
use App\Service\GeocoderManager;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;
use PHPUnit\Framework\TestCase;

class GeocoderManagerTest extends TestCase
{
    private ResolvedAddressRepository $resolvedAddressRepositoryMock;
    private GeocoderInterface $geocoderMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->resolvedAddressRepositoryMock = $this->createMock(ResolvedAddressRepository::class);
        $this->geocoderMock = $this->createMock(GeocoderInterface::class);
    }

    public function testHitsDatabaseAndReturnsNull()
    {
        $geocoderManager = new GeocoderManager([$this->geocoderMock], $this->resolvedAddressRepositoryMock);

        $this->resolvedAddressRepositoryMock->expects($this->once())->method('getByAddress')->willReturn(
            new ResolvedAddress(new Address('test', 'test', 'test', 'test'), null)
        );

        $this->geocoderMock->expects($this->never())->method('geocode');
        $this->resolvedAddressRepositoryMock->expects($this->never())->method('saveResolvedAddress');

        $result = $geocoderManager->geocode(new Address('test', 'test', 'test', 'test'));

        $this->assertNull($result);
    }

    public function testHitsDatabaseAndReturnsResult()
    {
        $geocoderManager = new GeocoderManager([$this->geocoderMock], $this->resolvedAddressRepositoryMock);

        $expectedAddress = new ResolvedAddress(
            new Address('test', 'test', 'test', 'test'),
            new Coordinates(1, 1)
        );

        $this->resolvedAddressRepositoryMock->expects($this->once())->method('getByAddress')
            ->willReturn($expectedAddress);

        $this->geocoderMock->expects($this->never())->method('geocode');
        $this->resolvedAddressRepositoryMock->expects($this->never())->method('saveResolvedAddress');

        $result = $geocoderManager->geocode(new Address('test', 'test', 'test', 'test'));

        $this->assertEquals($expectedAddress->getCoordinates()->getLat(), $result->getLat());
        $this->assertEquals($expectedAddress->getCoordinates()->getLng(), $result->getLng());
    }

    public function testsGeocodesAndSaves()
    {
        $geocoderManager = new GeocoderManager([$this->geocoderMock], $this->resolvedAddressRepositoryMock);

        $this->resolvedAddressRepositoryMock->expects($this->once())->method('getByAddress')
            ->willReturn(null);

        $address = new Address('test', 'test', 'test', 'test');
        $coordinates = new Coordinates(1, 1);

        $this->geocoderMock->expects($this->once())->method('geocode')->willReturn($coordinates);
        $this->resolvedAddressRepositoryMock->expects($this->once())
            ->method('saveResolvedAddress')
            ->with($address,$coordinates);

        $result = $geocoderManager->geocode($address);

        $this->assertEquals($coordinates->getLat(), $result->getLat());
        $this->assertEquals($coordinates->getLng(), $result->getLng());
    }
}
