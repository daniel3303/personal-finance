<?php

namespace App\Repository\Account;

use App\Entity\Account\AssetAccount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AssetAccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssetAccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssetAccount[]    findAll()
 * @method AssetAccount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssetAccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssetAccount::class);
    }

    // /**
    //  * @return AssetAccount[] Returns an array of AssetAccount objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AssetAccount
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
