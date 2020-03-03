<?php

namespace App\Dto\Transaction;

use App\Entity\Account\Account;
use App\Entity\TaxPayer\TaxPayer;
use App\Entity\Transaction\Transaction;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;

abstract class TransactionData {
    /**
     * @Assert\Length(max=128)
     */
    private ?string $title = null;

    /**
     * @Assert\NotNull()
     */
    private ?float $total = null;


    /**
     * @Assert\NotNull()
     */
    private ?DateTime $time = null;

    /**
     * @Assert\Length(max=65535)
     */
    private ?string $description = null;

    /**
     * @Assert\NotNull()
     */
    private ?Account $account = null;

    /**
     * @Assert\NotNull()
     */
    private ?TaxPayer $taxPayer = null;

    public function __construct(Transaction $transaction = null) {
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void {
        $this->title = $title;
    }

    /**
     * @return float|null
     */
    public function getTotal(): ?float {
        return $this->total;
    }

    /**
     * @param float|null $total
     */
    public function setTotal(?float $total): void {
        $this->total = $total;
    }

    /**
     * @return DateTime|null
     */
    public function getTime(): ?DateTime {
        return $this->time;
    }

    /**
     * @param DateTime|null $time
     */
    public function setTime(?DateTime $time): void {
        $this->time = $time;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void {
        $this->description = $description;
    }

    /**
     * @return Account|null
     */
    public function getAccount(): ?Account {
        return $this->account;
    }

    /**
     * @param Account|null $account
     */
    public function setAccount(?Account $account): void {
        $this->account = $account;
    }

    /**
     * @return TaxPayer|null
     */
    public function getTaxPayer(): ?TaxPayer {
        return $this->taxPayer;
    }

    /**
     * @param TaxPayer|null $taxPayer
     */
    public function setTaxPayer(?TaxPayer $taxPayer): void {
        $this->taxPayer = $taxPayer;
    }

    protected function transactionTransfer(Transaction $transaction): void {
        $transaction->setTitle($this->title);
        $transaction->setTotal($this->total);
        $transaction->setDescription($this->description);
        $transaction->setAccount($this->account);
        $transaction->setTaxPayer($this->taxPayer);
        $transaction->setTime($this->getTime());
    }

    protected function transactionReverseTransfer(Transaction $transaction): void {
        $this->title = $transaction->getTitle();
        $this->total = $transaction->getTotal();
        $this->description = $transaction->getDescription();
        $this->account = $transaction->getAccount();
        $this->taxPayer = $transaction->getTaxPayer();
        $this->time = $transaction->getTime();
    }

}
