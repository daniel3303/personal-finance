<?php

namespace App\Dto\Transaction;

use App\Entity\Transaction\Expense;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ExpenseData extends TransactionData {
    private ?Expense $entity = null;

    public function __construct(Expense $expense = null) {
        parent::__construct($expense);
        $this->entity = $expense;
        if($expense !== null){
            $this->reverseTransfer($expense);
        }
    }

    public function getEntity() : ?Expense{
        return $this->entity;
    }

    public function transfer(Expense $expense): void {
        $this->transactionTransfer($expense);
    }

    public function reverseTransfer(Expense $expense): void {
        $this->transactionReverseTransfer($expense);
    }

    public function createOrUpdateEntity(): Expense{
        if($this->entity === null){
            $this->entity = new Expense($this->getTotal(), $this->getTime(), $this->getAccount(), $this->getTaxPayer());
        }
        $this->transfer($this->entity);
        return $this->entity;
    }

    public static function validate(ExpenseData $expense, ExecutionContextInterface $context, $payload): void {
        // Revenue total must be equal or lesser than 0
        if ($expense->getTotal() > 0) {
            $context->buildViolation('The total amount of the revenue must be lesser or equal to 0.')
                ->atPath('total')
                ->addViolation();
        }
    }
}
