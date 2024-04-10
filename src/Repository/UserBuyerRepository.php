<?php

namespace App\Repository;

use App\Entity\UserBuyer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserBuyer>
 *
 * @method UserBuyer|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserBuyer|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserBuyer[]    findAll()
 * @method UserBuyer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserBuyerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserBuyer::class);
    }

    //    /**
    //     * @return UserBuyer[] Returns an array of UserBuyer objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?UserBuyer
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
