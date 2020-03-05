<?php

namespace App\Repository\TaxPayer;

use App\Entity\TaxPayer\TaxPayer;
use App\Repository\BaseRepository;
use App\Repository\UserAwareRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TaxPayer|null find($id, $lockMode = null, $lockVersion = null)
 * @method TaxPayer|null findOneBy(array $criteria, array $orderBy = null)
 * @method TaxPayer[]    findAll()
 * @method TaxPayer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaxPayerRepository extends UserAwareRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, TaxPayer::class);
    }

}
