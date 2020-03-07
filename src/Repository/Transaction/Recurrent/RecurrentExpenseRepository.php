<?php

namespace App\Repository\Transaction\Recurrent;

use App\Entity\Transaction\Expense;
use App\Entity\Transaction\Recurrent\RecurrentExpense;
use App\Repository\UserAwareRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Expense|null find($id, $lockMode = null, $lockVersion = null)
 * @method Expense|null findOneBy(array $criteria, array $orderBy = null)
 * @method Expense[]    findAll()
 * @method Expense[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecurrentExpenseRepository extends UserAwareRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, RecurrentExpense::class);
    }

    // /**
    //  * @return Expense[] Returns an array of Expense objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Expense
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
