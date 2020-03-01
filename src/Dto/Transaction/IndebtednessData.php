<?php

namespace App\Dto\Transaction;

use App\Entity\Transaction\Indebtedness;
use App\Entity\Transaction\Transaction;

class IndebtednessData extends TransactionData {

    private ?Indebtedness $entity = null;

    public function __construct(Indebtedness $indebtedness = null) {
        parent::__construct($indebtedness);
        $this->entity = $indebtedness;
        if($indebtedness !== null){
            $this->reverseTransfer($indebtedness);
        }
    }

    public function getEntity() : ?Indebtedness{
        return $this->entity;
    }

    public function transfer(Indebtedness $indebtedness): void {
        $this->transactionTransfer($indebtedness);
    }

    public function reverseTransfer(Indebtedness $indebtedness): void {
        $this->transactionReverseTransfer($indebtedness);
    }

    public function createOrUpdateEntity(): Indebtedness{
        if($this->entity === null){
            $this->entity = new Indebtedness($this->getTotal(), $this->getTime(), $this->getAccount(), $this->getTaxPayer());
        }
        $this->transfer($this->entity);
        return $this->entity;
    }
}
