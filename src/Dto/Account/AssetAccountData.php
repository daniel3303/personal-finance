<?php

namespace App\Dto\Account;

use App\Entity\Account\Account;
use App\Entity\Account\AssetAccount;
use Doctrine\ORM\Mapping as ORM;

class AssetAccountData extends AccountData {
    private ?AssetAccount $entity;

    public function __construct(?AssetAccount $assetAccount = null) {
        parent::__construct($assetAccount);
        $this->entity = $assetAccount;
    }

    public function createOrUpdateEntity(): AssetAccount {
        if($this->entity === null){
            $this->entity = new AssetAccount($this->getName(), $this->getTotal(), $this->getInitialAmountTime());
        }
        $this->updateEntity($this->entity);
        return $this->entity;
    }
}
