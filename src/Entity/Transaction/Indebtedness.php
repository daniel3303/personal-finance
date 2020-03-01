<?php

namespace App\Entity\Transaction;

use App\Entity\TaxPayer\TaxPayer;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transaction\ExpenseRepository")
 */
class Indebtedness extends Transaction {
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
            $olderTaxPayer->removeIndebtedness($this);
        }
        if($taxPayer){
            $taxPayer->addIndebtedness($this);
        }
        return $this;
    }
}
