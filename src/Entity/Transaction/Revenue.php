<?php

namespace App\Entity\Transaction;

use App\Entity\TaxPayer\TaxPayer;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transaction\RevenueRepository")
 */
class Revenue extends Transaction {
    public static function validate(Revenue $revenue, ExecutionContextInterface $context, $payload): void {

        // Revenue total must be equal or greater than 0
        if ($revenue->getTotal() < 0) {
            $context->buildViolation('The total amount of the revenue must be greater or equal to 0.')
                ->atPath('total')
                ->addViolation();
        }
    }
}
