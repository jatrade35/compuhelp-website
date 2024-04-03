<?php

namespace App\Repository;

use App\Entity\TestimonialLang;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TestimonialLang>
 *
 * @method TestimonialLang|null find($id, $lockMode = null, $lockVersion = null)
 * @method TestimonialLang|null findOneBy(array $criteria, array $orderBy = null)
 * @method TestimonialLang[]    findAll()
 * @method TestimonialLang[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestimonialLangRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TestimonialLang::class);
    }

    //    /**
    //     * @return TestimonialLang[] Returns an array of TestimonialLang objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?TestimonialLang
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
