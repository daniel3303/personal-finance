<?php

namespace App\Repository\Transaction;

use App\Entity\Transaction\Revenue;
use App\Repository\BaseRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Revenue|null find($id, $lockMode = null, $lockVersion = null)
 * @method Revenue|null findOneBy(array $criteria, array $orderBy = null)
 * @method Revenue[]    findAll()
 * @method Revenue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RevenueRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Revenue::class);
    }
    
}
