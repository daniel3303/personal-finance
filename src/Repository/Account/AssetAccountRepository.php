<?php

namespace App\Repository\Account;

use App\Entity\Account\AssetAccount;
use App\Repository\BaseRepository;
use App\Repository\UserAwareRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AssetAccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssetAccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssetAccount[]    findAll()
 * @method AssetAccount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssetAccountRepository extends UserAwareRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, AssetAccount::class);
    }
}
