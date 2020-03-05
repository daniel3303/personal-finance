<?php

namespace App\Repository\Transaction;

use App\Entity\Transaction\Indebtedness;
use App\Repository\BaseRepository;
use App\Repository\UserAwareRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Indebtedness|null find($id, $lockMode = null, $lockVersion = null)
 * @method Indebtedness|null findOneBy(array $criteria, array $orderBy = null)
 * @method Indebtedness[]    findAll()
 * @method Indebtedness[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndebtednessRepository extends UserAwareRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Indebtedness::class);
    }

}
