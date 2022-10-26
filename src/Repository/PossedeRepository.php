<?php

namespace App\Repository;

use App\Entity\Possede;
use App\Entity\Compose;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Possede|null find($id, $lockMode = null, $lockVersion = null)
 * @method Possede|null findOneBy(array $criteria, array $orderBy = null)
 * @method Possede[]    findAll()
 * @method Possede[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PossedeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Possede::class);
    }

    public function findPossessionsByUser($id)
    {
        return $this    -> getEntityManager()
                        -> createQuery( 
                            'SELECT a
                            FROM App\entity\Possede a
                            WHERE a.user = :id
                        ')
                        ->setParameter('id', $id)
                        ->getResult();
    }

    public function findPossessionByUserAndCard($userId, $cardId)
    {
        return $this    -> getEntityManager()
                        -> createQuery( 
                            'SELECT a
                            FROM App\entity\Possede a
                            WHERE a.carte = :cardId
                            AND a.user = :userId
                        ')
                        ->setParameter('cardId', $cardId)
                        ->setParameter('userId', $userId)
                        ->getResult();
    }

    public function deletePossessionByUserAndCard($userId, $cardId)
    {
        return $this    -> getEntityManager()
                        -> createQuery( 
                            'DELETE 
                            FROM App\entity\Possede a
                            WHERE a.user = :userId
                            AND a.carte = :cardId
                        ')
                        ->setParameter('userId', $userId)
                        ->setParameter('cardId', $cardId)
                        ->getResult();
    }

    // /**
    //  * @return Possede[] Returns an array of Possede objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Possede
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
