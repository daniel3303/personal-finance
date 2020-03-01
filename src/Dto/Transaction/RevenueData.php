<?php

namespace App\Dto\Transaction;

use App\Entity\Transaction\Revenue;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RevenueData extends TransactionData {
    private ?Revenue $entity;

    public function __construct(Revenue $revenue = null) {
        parent::__construct($revenue);
        $this->entity = $revenue;
        if($revenue !== null){
            $this->reverseTransfer($revenue);
        }
    }

    public function createOrUpdateEntity(): Revenue{
        if($this->entity === null){
            $this->entity = new Revenue($this->getTotal(), $this->getTime(), $this->getAccount(), $this->getTaxPayer());
        }
        $this->transactionTransfer($this->entity);
        return $this->entity;
    }

    public function getEntity() : ?Revenue{
        return $this->entity;
    }

    public static function validate(RevenueData $revenueData, ExecutionContextInterface $context, $payload): void {
        // Revenue total must be equal or greater than 0
        if ($revenueData->getTotal() < 0) {
            $context->buildViolation('The total amount of the revenue must be greater or equal to 0.')
                ->atPath('total')
                ->addViolation();
        }
    }

    public function transfer(Revenue $revenue): void {
        $this->transactionTransfer($revenue);
    }

    public function reverseTransfer(Revenue $revenue): void {
        $this->transactionReverseTransfer($revenue);
    }
}
