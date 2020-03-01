<?php

namespace App\Entity\Account;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Account\AssetAccountRepository")
 */
class AssetAccount extends Account {
    public function __construct(string $name, float $total, DateTime $initialAmountTime) {
        parent::__construct($name, $total, $initialAmountTime);
    }
}
