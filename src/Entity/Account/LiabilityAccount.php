<?php

namespace App\Entity\Account;

use Carbon\CarbonInterval;
use DateInterval;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Account\LiabilityAccountRepository")
 */
class LiabilityAccount extends Account {
    /**
     * @ORM\Column(type="float")
     */
    private float $interest = 0;

    /**
     * @ORM\Column(type="dateinterval")
     */
    private ?DateInterval $interestInterval = null;

    public function getInterest(): ?float {
        return $this->interest;
    }

    public function setInterest(float $interest): self {
        $this->interest = $interest;

        return $this;
    }

    public function getInterestInterval(): ?CarbonInterval {
        return $this->interestInterval !== null ? CarbonInterval::instance($this->interestInterval) : null;
    }

    public function setInterestInterval(DateInterval $interestInterval): self {
        $this->interestInterval = $interestInterval;

        return $this;
    }
}
