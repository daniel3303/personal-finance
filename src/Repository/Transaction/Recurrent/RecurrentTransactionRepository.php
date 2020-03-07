<?php

namespace App\Repository\Transaction\Recurrent;

use App\Entity\Transaction\Recurrent\RecurrentTransaction;
use App\Repository\BaseRepository;
use App\Repository\UserAwareRepository;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;

/**
 * @method RecurrentTransaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecurrentTransaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecurrentTransaction[]    findAll()
 * @method RecurrentTransaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecurrentTransactionRepository extends UserAwareRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, RecurrentTransaction::class);
    }

    /**
     * @param DateTime|null $date
     * @return RecurrentTransaction[]|Paginator
     * @throws Exception
     */
    public function findRequiringUpdate(?DateTime $date = null) : Paginator{
        if($date === null) {
            $date = new DateTime();
        }
        return new Paginator($this->createQueryBuilder('t')
            ->select('t')
            ->where('t.nextTransactionCreationTime <= :now')
            ->andWhere('t.enabled = true')
            ->setParameter('now', $date)->getQuery());
    }
}
