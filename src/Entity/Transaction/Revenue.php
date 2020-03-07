<?php

namespace App\Entity\Transaction;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transaction\RevenueRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorMap({
 *     "recurrentRevenue" = "App\Entity\Transaction\Recurrent\RecurrentRevenue",
 * })
 */
class Revenue extends Transaction {
}
