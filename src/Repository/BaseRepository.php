<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-04-28
 * Time: 01:43
 */

namespace App\Repository;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;

abstract class BaseRepository extends ServiceEntityRepository {
    public function findAllWithQuery(): Query {
        return $this->createQueryBuilder('o')->select('o')->getQuery();
    }
}