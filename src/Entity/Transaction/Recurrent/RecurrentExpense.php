<?php

namespace App\Entity\Transaction\Recurrent;

use App\Entity\Account\Account;
use App\Entity\Category\Category;
use App\Entity\TaxPayer\TaxPayer;
use App\Entity\Transaction\Expense;
use App\Entity\User\User;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transaction\Recurrent\RecurrentExpenseRepository")
 */
class RecurrentExpense extends Expense {
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transaction\Recurrent\RecurrentTransaction", inversedBy="expenses")
     * @ORM\JoinColumn(nullable=false)
     */
    private RecurrentTransaction $recurrentTransaction;

    public function __construct(User $user, string $title, float $total, DateTime $time, Account $account, TaxPayer $taxPayer, Category $category, RecurrentTransaction $recurrentTransaction) {
        parent::__construct($user, $title, $total, $time, $account, $taxPayer, $category);
        $this->recurrentTransaction = $recurrentTransaction;
    }

    public function getRecurrentTransaction(): RecurrentTransaction {
        return $this->recurrentTransaction;
    }
}
