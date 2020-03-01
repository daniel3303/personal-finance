<?php

namespace App\Dto\Account;

use App\Entity\Account\Account;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;

abstract class AccountData {
    /**
     * @Assert\NotNull()
     * @Assert\Length(min=1, max=64)
     */
    private ?string $name = null;

    /**
     * @Assert\NotNull()
     */
    private ?float $total = null;

    /**
     * @Assert\NotNull()
     */
    private ?DateTime $initialAmountTime = null;

    /**
     * @var string|null
     * @Assert\Length(min=34, max=34)
     */
    private ?string $iban = null;

    /**
     * @Assert\Length(max=65535)
     */
    private ?string $notes = null;

    public function __construct(?Account $account) {
        if($account){
            $this->name = $account->getName();
            $this->iban = $account->getIban();
            $this->total = $account->getTotal();
            $this->initialAmountTime = $account->getInitialAmountTime();
            $this->notes = $account->getNotes();
        }
    }


    public function getName(): ?string {
        return $this->name;
    }

    public function setName(?string $name): self {
        $this->name = $name;

        return $this;
    }

    public function getTotal(): ?float {
        return $this->total;
    }

    public function setTotal(?float $total): self {
        $this->total = $total;

        return $this;
    }

    public function getNotes(): ?string {
        return $this->notes;
    }

    public function setNotes(?string $notes): self {
        $this->notes = $notes;

        return $this;
    }

    public function getInitialAmountTime(): ?DateTime {
        return $this->initialAmountTime;
    }

    public function setInitialAmountTime(?DateTime $initialAmountTime): self {
        $this->initialAmountTime = $initialAmountTime;

        return $this;
    }

    public function getIban(): ?string {
        return $this->iban;
    }

    public function setIban(?string $iban): self {
        $this->iban = $iban;

        return $this;
    }

    public function updateEntity(Account $account) : void {
        $account->setName($this->name);
        $account->setIban($this->iban);
        $account->setNotes($this->notes);
        $account->setTotal($this->total);
        $account->setInitialAmountTime($this->initialAmountTime);
    }
}
