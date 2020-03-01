<?php

namespace App\Dto\Transaction;

use App\Entity\Transaction\Indebtedness;

class IndebtednessData extends TransactionData {

    private ?Indebtedness $entity;

    public function __construct(Indebtedness $indebtedness = null) {
        parent::__construct($indebtedness);
        $this->entity = $indebtedness;
    }

    public function createOrUpdateEntity(): Indebtedness{
        if($this->entity === null){
            $this->entity = new Indebtedness($this->getTotal(), $this->getTime(), $this->getAccount(), $this->getTaxPayer());
        }
        $this->updateEntity($this->entity);
        return $this->entity;
    }
}
