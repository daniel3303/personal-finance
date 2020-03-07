<?php

namespace App\Repository\Transaction\Recurrent;

use App\Entity\Transaction\Revenue;
use App\Repository\BaseRepository;
use App\Repository\UserAwareRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Revenue|null find($id, $lockMode = null, $lockVersion = null)
 * @method Revenue|null findOneBy(array $criteria, array $orderBy = null)
 * @method Revenue[]    findAll()
 * @method Revenue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecurrentRevenueRepository extends UserAwareRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Revenue::class);
    }

}
