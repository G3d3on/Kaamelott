<?php

namespace App\Repository;

use App\Entity\Compose;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Compose|null find($id, $lockMode = null, $lockVersion = null)
 * @method Compose|null findOneBy(array $criteria, array $orderBy = null)
 * @method Compose[]    findAll()
 * @method Compose[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComposeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Compose::class);
    }

    public function findCompositionsByDeck($deckId)
    {
        return $this    -> getEntityManager()
                        -> createQuery( 
                            'SELECT a
                            FROM App\entity\Compose a
                            WHERE a.deck = :deckId
                        ')
                        ->setParameter('deckId', $deckId)
                        ->getResult();
    }

    public function findCompositionByDeckAndCard($deckId ,$cardId)
    {
        return $this    -> getEntityManager()
                        -> createQuery( 
                            'SELECT a
                            FROM App\entity\Compose a
                            WHERE a.carte = :cardId
                            AND a.deck = :deckId
                        ')
                        ->setParameter('cardId', $cardId)
                        ->setParameter('deckId', $deckId)
                        ->getResult();
    }

    public function deleteComposition($id)
    {
        return $this    -> getEntityManager()
                        -> createQuery( 
                            'DELETE 
                            FROM App\entity\Compose a
                            WHERE a.id = :id
                        ')
                        ->setParameter('id', $id)
                        ->getResult();
    }

    // /**
    //  * @return Compose[] Returns an array of Compose objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Compose
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
