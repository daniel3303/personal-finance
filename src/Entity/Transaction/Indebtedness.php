<?php

namespace App\Entity\Transaction;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transaction\IndebtednessRepository")
 */
class Indebtedness extends Transaction {
}
