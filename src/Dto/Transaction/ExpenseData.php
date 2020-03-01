<?php

namespace App\Dto\Transaction;

use App\Entity\TaxPayer\TaxPayer;
use App\Entity\Transaction\Expense;
use App\Entity\Transaction\Transaction;
use Doctrine\ORM\Mapping as ORM;
use mysql_xdevapi\XSession;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ExpenseData extends TransactionData {
    private ?Expense $entity = null;

    public function __construct(Expense $expense = null) {
        parent::__construct($expense);
        $this->entity = $expense;
    }

    public function getEntity() : ?Expense{
        return $this->entity;
    }

    public function createOrUpdateEntity(): Expense{
        if($this->entity === null){
            $this->entity = new Expense($this->getTotal(), $this->getTime(), $this->getAccount(), $this->getTaxPayer());
        }
        $this->updateEntity($this->entity);
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
