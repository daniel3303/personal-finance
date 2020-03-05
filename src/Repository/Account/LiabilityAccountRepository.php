<?php

namespace App\Repository\Account;

use App\Entity\Account\LiabilityAccount;
use App\Repository\UserAwareRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method LiabilityAccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method LiabilityAccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method LiabilityAccount[]    findAll()
 * @method LiabilityAccount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LiabilityAccountRepository extends UserAwareRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LiabilityAccount::class);
    }

    // /**
    //  * @return LiabilityAccount[] Returns an array of LiabilityAccount objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LiabilityAccount
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
