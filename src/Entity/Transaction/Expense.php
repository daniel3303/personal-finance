<?php

namespace App\Entity\Transaction;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transaction\ExpenseRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorMap({
 *     "recurrentExpense" = "App\Entity\Transaction\Recurrent\RecurrentExpense",
 * })
 */
class Expense extends Transaction {
}
