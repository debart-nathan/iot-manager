<?php

namespace App\Repository;

use App\Entity\ModuleType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ModuleType>
 *
 * @method ModuleType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModuleType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModuleType[]    findAll()
 * @method ModuleType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModuleTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModuleType::class);
    }

//    /**
//     * @return ModuleType[] Returns an array of ModuleType objects
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

//    public function findOneBySomeField($value): ?ModuleType
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
