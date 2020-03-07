<?php

namespace App\Repository\CronJob;

use App\Entity\CronJob\CronJob;
use App\Repository\BaseRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CronJob|null find($id, $lockMode = null, $lockVersion = null)
 * @method CronJob|null findOneBy(array $criteria, array $orderBy = null)
 * @method CronJob[]    findAll()
 * @method CronJob[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CronJobRepository extends BaseRepository {
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, CronJob::class);
    }

    public function findLastCronJobForService(string $service) : ?CronJob{
       try {
           return $this->createQueryBuilder('c')
               ->select('c')
               ->where('c.service = :service')
               ->orderBy('c.executionTime', 'DESC')
               ->setParameter('service', $service)
               ->setMaxResults(1)
               ->getQuery()
               ->getSingleResult();
       } catch (NoResultException $e) {
           return null;
       } catch (NonUniqueResultException $e) {
           return null;
       }
   }
}
