<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-04-28
 * Time: 01:43
 */

namespace App\Repository;


use App\Entity\User\User;
use Doctrine\ORM\Query;

abstract class UserAwareRepository extends BaseRepository {
    public function findAllForUserWithQuery(User $user): Query {
        return $this->createQueryBuilder('o')
            ->select('o')
            ->where('o.user = :user')
            ->setParameter('user', $user)
            ->getQuery();
    }
}