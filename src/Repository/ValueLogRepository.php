<?php

namespace App\Repository;

use App\Entity\ValueLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ValueLog>
 *
 * @method ValueLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method ValueLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method ValueLog[]    findAll()
 * @method ValueLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ValueLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ValueLog::class);
    }

//    /**
//     * @return ValueLog[] Returns an array of ValueLog objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ValueLog
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
