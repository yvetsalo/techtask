<?php

namespace App\Repository;

use App\Entity\ResolvedAddress as ResolvedAddressEntity;
use App\ValueObject\ResolvedAddress;
use App\ValueObject\Address;
use App\ValueObject\Coordinates;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ResolvedAddressEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResolvedAddressEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResolvedAddressEntity[]    findAll()
 * @method ResolvedAddressEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResolvedAddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResolvedAddressEntity::class);
    }

    public function getByAddress(Address $address): ?ResolvedAddress
    {
        $resolvedAddress = $this->findOneBy([
            'countryCode' => $address->getCountry(),
            'city' => $address->getCity(),
            'street' => $address->getStreet(),
            'postcode' => $address->getPostcode()
        ]);

        if(!$resolvedAddress){
            return null;
        }

        $coordinates = null;
        if ($resolvedAddress->getLat() && $resolvedAddress->getLng()) {
            $coordinates = new Coordinates($resolvedAddress->getLat(), $resolvedAddress->getLng());
        }

        return new ResolvedAddress(
            $address,
            $coordinates
        );
    }

    public function saveResolvedAddress(Address $address, ?Coordinates $coordinates): void
    {
        $resolvedAddress = new ResolvedAddress();
        $resolvedAddress
            ->setCountryCode($address->getCountry())
            ->setCity($address->getCity())
            ->setStreet($address->getStreet())
            ->setPostcode($address->getPostcode());

        if ($coordinates !== null) {
            $resolvedAddress
                ->setLat((string)$coordinates->getLat())
                ->setLng((string)$coordinates->getLng());
        }

        $this->getEntityManager()->persist($resolvedAddress);
        $this->getEntityManager()->flush();
    }
}
