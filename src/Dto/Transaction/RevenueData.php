<?php

namespace App\Dto\Transaction;

use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RevenueData extends TransactionData {
    public static function validate(RevenueData $revenueData, ExecutionContextInterface $context, $payload): void {

        // Revenue total must be equal or greater than 0
        if ($revenueData->getTotal() < 0) {
            $context->buildViolation('The total amount of the revenue must be greater or equal to 0.')
                ->atPath('total')
                ->addViolation();
        }
    }
}
