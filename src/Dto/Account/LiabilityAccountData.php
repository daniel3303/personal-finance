<?php

namespace App\Dto\Account;

use App\Entity\Account\AssetAccount;
use App\Entity\Account\LiabilityAccount;
use DateInterval;
use Symfony\Component\Validator\Constraints as Assert;

class LiabilityAccountData extends AccountData {
    private ?LiabilityAccount $entity = null;

    /**
     * @Assert\NotNull()
     * @Assert\GreaterThan(value=0)
     */
    private ?float $interest = null;

    /**
     * @Assert\NotNull()
     */
    private ?DateInterval $interestInterval = null;

    public function __construct(?AssetAccount $assetAccount = null) {
        parent::__construct($assetAccount);
        $this->entity = $assetAccount;
    }

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

    public function getEntity() : ?LiabilityAccount{
        return $this->entity;
    }

    public function createOrUpdateEntity(): LiabilityAccount {
        if($this->entity === null){
            $this->entity = new LiabilityAccount($this->getName(), $this->getTotal(), $this->getInitialAmountTime(), $this->interest, $this->interestInterval);
        }
        $this->updateEntity($this->entity);
        return $this->entity;
    }
}
