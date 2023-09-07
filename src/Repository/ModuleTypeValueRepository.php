<?php

namespace App\Repository;

use App\Entity\ModuleTypeValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ModuleTypeValue>
 *
 * @method ModuleTypeValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModuleTypeValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModuleTypeValue[]    findAll()
 * @method ModuleTypeValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModuleTypeValueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModuleTypeValue::class);
    }

//    /**
//     * @return ModuleTypeValue[] Returns an array of ModuleTypeValue objects
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

//    public function findOneBySomeField($value): ?ModuleTypeValue
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
