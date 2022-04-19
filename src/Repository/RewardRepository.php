<?php

namespace App\Repository;

use App\Entity\Project;
use App\Entity\Reward;
use App\Exceptions\EntityNotFoundException;
use App\Interfaces\entities\RewardEntityInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reward|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reward|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reward[]    findAll()
 * @method Reward[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RewardRepository extends ServiceEntityRepository implements RewardEntityInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reward::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Project $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Project $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findByName(string $rewardName): Reward
    {
        try {
            return $this->createQueryBuilder('reward')
                ->where('LOWER(reward.name) = LOWER(:rewardName)')
                ->setParameters([
                    'rewardName' => $rewardName,
                ])
                ->getQuery()
                ->getSingleResult();
        } catch (NoResultException $e) {
            throw new EntityNotFoundException(Reward::class, ['name' => $rewardName]);
        } catch (NonUniqueResultException $e) {
            echo $e->getMessage();
            throw $e;
        }
    }

    public function persist(Reward $reward): void
    {
        try {
            $this->_em->persist($reward);
        } catch (ORMException $e) {
        }
    }

    public function persistAndFlush(Reward $reward): void
    {
        try {
            $this->_em->persist($reward);
        } catch (ORMException $e) {
        }
        try {
            $this->_em->flush();
        } catch (OptimisticLockException | ORMException $e) {
        }
    }

    public function findById(int $id): Reward
    {
        return $this->createQueryBuilder('reward')
            ->where('reward.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult()
            ;
    }
}
