<?php

namespace App\Dto\Account;

use App\Entity\Account\AssetAccount;

class AssetAccountData extends AccountData {
    private ?AssetAccount $entity = null;

    public function __construct(?AssetAccount $assetAccount = null) {
        parent::__construct($assetAccount);
        $this->entity = $assetAccount;
        if($assetAccount){
            $this->reverseTransfer($assetAccount);
        }
    }

    public function getEntity() : ?AssetAccount{
        return $this->entity;
    }

    public function transfer(AssetAccount $assetAccount): void {
        $this->accountTransfer($assetAccount);
    }

    public function reverseTransfer(AssetAccount $assetAccount): void {
        $this->accountReverseTransfer($assetAccount);
    }

    public function createOrUpdateEntity(): AssetAccount {
        if($this->entity === null){
            $this->entity = new AssetAccount($this->getUser(), $this->getName(), $this->getTotal(), $this->getInitialAmountTime());
        }
        $this->transfer($this->entity);
        return $this->entity;
    }
}
