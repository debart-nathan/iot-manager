<?php

namespace App\Repository;

use App\Entity\Module;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Module>
 *
 * @method Module|null find($id, $lockMode = null, $lockVersion = null)
 * @method Module|null findOneBy(array $criteria, array $orderBy = null)
 * @method Module[]    findAll()
 * @method Module[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Module::class);
    }

    public function findDistinctStatusInModules(): array
    {
        $qb = $this->createQueryBuilder('m')
            ->select('s.status_name')
            ->join('m.status_name', 's')
            ->distinct();

        $query = $qb->getQuery();

        return $query->execute();
    }

    public function findWithFiltersAndSort(array $filters, ?string $sort, ?string $order): array
    {
        $qb = $this->createQueryBuilder('m');
        $qb->join('m.status_name', 's');


        // Apply filters
        if (!empty($filters['status_name'])) {
            $qb->andWhere('m.status_name = :status_name')
                ->setParameter('status_name', $filters['status_name']);
        }

        if (!empty($filters['module_type_name'])) {
            $qb->andWhere('m.module_type_name = :module_type_name')
                ->setParameter('module_type_name', $filters['module_type_name']);
        }

        if (!empty($filters['name'])) {
            $qb->andWhere('m.module_name LIKE :name')
                ->setParameter('name', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['model'])) {
            $qb->andWhere('m.model LIKE :model')
                ->setParameter('model', '%' . $filters['model'] . '%');
        }

        if (!empty($filters['reference_code'])) {
            $qb->andWhere('m.reference_code LIKE :reference_code')
                ->setParameter('reference_code', '%' . $filters['reference_code'] . '%');
        }

        if (!empty($filters['status_category'])) {
            $qb->andWhere('s.status_category = :status_category')
                ->setParameter('status_category', $filters['status_category']);
        }

        // Apply sort
        if (
            isset($sort) && !empty($sort) &&
            isset($order) && !empty($order) &&
            ($order === "ASC" || $order === "DESC")
        ) {
            if ($sort === "log_date") {
                $em = $this->getEntityManager();
                $sub = $em->createQueryBuilder()
                    ->select('CASE WHEN COUNT(v) > 0 THEN ' .
                        ($order === 'ASC' ? 'MIN(v.log_date)' : 'MAX(v.log_date)') .
                        ' ELSE \'1000-01-01 00:00:00\' END')
                    ->from('App\Entity\ValueLog', 'v')
                    ->where('v.module_id = m.module_id');
                $qb->addSelect('(' . $sub->getDQL() . ') AS HIDDEN log_date')
                    ->orderBy('log_date', $order);
            } else {
                $qb->orderBy('m.' . $sort, $order);
            }
        }

        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return Module[] Returns an array of Module objects
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

    //    public function findOneBySomeField($value): ?Module
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


}
