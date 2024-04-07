<?php

namespace App\Repository;

use App\Entity\MediaSeller;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MediaSeller>
 *
 * @method MediaSeller|null find($id, $lockMode = null, $lockVersion = null)
 * @method MediaSeller|null findOneBy(array $criteria, array $orderBy = null)
 * @method MediaSeller[]    findAll()
 * @method MediaSeller[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaSellerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediaSeller::class);
    }

    //    /**
    //     * @return MediaSeller[] Returns an array of MediaSeller objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?MediaSeller
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
