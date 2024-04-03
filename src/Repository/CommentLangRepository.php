<?php

namespace App\Repository;

use App\Entity\CommentLang;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommentLang>
 *
 * @method CommentLang|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentLang|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentLang[]    findAll()
 * @method CommentLang[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentLangRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentLang::class);
    }

    //    /**
    //     * @return CommentLang[] Returns an array of CommentLang objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?CommentLang
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
