<?php

namespace App\Entity\Account;

use App\Entity\User\User;
use Carbon\CarbonInterval;
use DateInterval;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Account\LiabilityAccountRepository")
 */
class LiabilityAccount extends Account {
    /**
     * @ORM\Column(type="float")
     */
    private float $interest;

    /**
     * @ORM\Column(type="dateinterval")
     */
    private DateInterval $interestInterval;

    public function __construct(User $user, string $name, float $total, DateTime $initialAmountTime, float $interest, DateInterval $interestInterval) {
        parent::__construct($user, $name, $total, $initialAmountTime);
        $this->interest = $interest;
        $this->interestInterval = $interestInterval;
    }

    public function getInterest(): float {
        return $this->interest;
    }

    public function setInterest(float $interest): self {
        $this->interest = $interest;

        return $this;
    }

    public function getInterestInterval(): CarbonInterval {
        return CarbonInterval::instance($this->interestInterval);
    }

    public function setInterestInterval(DateInterval $interestInterval): self {
        $this->interestInterval = $interestInterval;

        return $this;
    }
}
