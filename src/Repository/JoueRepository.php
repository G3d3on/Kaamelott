<?php

namespace App\Repository;

use App\Entity\Joue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Joue|null find($id, $lockMode = null, $lockVersion = null)
 * @method Joue|null findOneBy(array $criteria, array $orderBy = null)
 * @method Joue[]    findAll()
 * @method Joue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JoueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Joue::class);
    }

    // /**
    //  * @return Joue[] Returns an array of Joue objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Joue
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
