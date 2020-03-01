<?php

namespace App\Entity\Transaction;

use App\Entity\TaxPayer\TaxPayer;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transaction\ExpenseRepository")
 */
class Expense extends Transaction {
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TaxPayer\TaxPayer", inversedBy="expenses")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull()
     */
    private ?TaxPayer $taxPayer = null;

    /**
     * @inheritDoc
     */
    public function getTaxPayer(): ?TaxPayer {
        return $this->taxPayer;
    }

    /**
     * @inheritDoc
     */
    public function setTaxPayer(?TaxPayer $taxPayer) {
        $olderTaxPayer = $this->taxPayer;
        $this->taxPayer = $taxPayer;

        if($olderTaxPayer && $olderTaxPayer !== $taxPayer){
            $olderTaxPayer->removeExpense($this);
        }
        if($taxPayer){
            $taxPayer->addExpense($this);
        }
        return $this;
    }

    public static function validate(Expense $expense, ExecutionContextInterface $context, $payload): void {

        // Revenue total must be equal or lesser than 0
        if ($expense->getTotal() > 0) {
            $context->buildViolation('The total amount of the revenue must be lesser or equal to 0.')
                ->atPath('total')
                ->addViolation();
        }
    }
}
