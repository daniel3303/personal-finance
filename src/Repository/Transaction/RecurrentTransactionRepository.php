<?php

namespace App\Repository\Transaction;

use App\Entity\Transaction\RecurrentTransaction;
use App\Repository\BaseRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method RecurrentTransaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecurrentTransaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecurrentTransaction[]    findAll()
 * @method RecurrentTransaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecurrentTransactionRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecurrentTransaction::class);
    }

    // /**
    //  * @return RecurrentTransaction[] Returns an array of RecurrentTransaction objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RecurrentTransaction
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
