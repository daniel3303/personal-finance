<?php

namespace App\Entity\Transaction;

use App\Entity\TaxPayer\TaxPayer;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transaction\IndebtednessRepository")
 */
class Indebtedness extends Transaction {
}
