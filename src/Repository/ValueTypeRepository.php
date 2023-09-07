<?php

namespace App\Repository;

use App\Entity\ValueType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ValueType>
 *
 * @method ValueType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ValueType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ValueType[]    findAll()
 * @method ValueType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ValueTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ValueType::class);
    }

//    /**
//     * @return ValueType[] Returns an array of ValueType objects
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

//    public function findOneBySomeField($value): ?ValueType
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
