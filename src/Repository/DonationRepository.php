<?php

namespace App\Repository;

use App\Entity\Donation;
use App\Entity\Person;
use App\Interfaces\entities\DonationEntityInterface;
use App\Interfaces\Gateways\DonationGatewayInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Donation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Donation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Donation[]    findAll()
 * @method Donation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DonationRepository extends ServiceEntityRepository implements DonationEntityInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Donation::class);
    }

    public function persist(Donation $donation): void
    {
        try {
            $this->_em->persist($donation);
        } catch (ORMException $e) {
        }
    }

    public function persistAndFlush(Donation $donation): void
    {
        try {
            $this->_em->persist($donation);
        } catch (ORMException $e) {
        }
        try {
            $this->_em->flush();
        } catch (OptimisticLockException | ORMException $e) {
        }
    }

    /**
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function getDonationsTotalAmountPerPerson(Person $person): int
    {
        $query = $this->createQueryBuilder('donations')
            ->select('SUM(donations.amount) as sum')
            ->andWhere('donations.person = :person')
            ->setParameter('person', $person);

        return (int) $query->getQuery()->getSingleScalarResult();
    }

    public function add(Donation $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Donation $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
