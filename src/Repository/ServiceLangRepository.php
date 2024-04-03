<?php

namespace App\Repository;

use App\Entity\ServiceLang;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ServiceLang>
 *
 * @method ServiceLang|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServiceLang|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServiceLang[]    findAll()
 * @method ServiceLang[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceLangRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServiceLang::class);
    }

//    /**
//     * @return ServiceLang[] Returns an array of ServiceLang objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ServiceLang
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
