<?php

namespace App\Dto\Account;

use DateInterval;
use Symfony\Component\Validator\Constraints as Assert;

class LiabilityAccountData extends AccountData {
    /**
     * @Assert\NotNull()
     * @Assert\GreaterThan(value=0)
     */
    private ?float $interest;

    /**
     * @Assert\NotNull()
     */
    private ?DateInterval $interestInterval;

    public function getInterest(): ?float {
        return $this->interest;
    }

    public function setInterest(?float $interest): self {
        $this->interest = $interest;

        return $this;
    }

    public function getInterestInterval(): ?DateInterval {
        return $this->interestInterval;
    }

    public function setInterestInterval(?DateInterval $interestInterval): self {
        $this->interestInterval = $interestInterval;

        return $this;
    }
}
