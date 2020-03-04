<?php

namespace App\Dto\Transaction;

use App\Entity\Category\Category;
use App\Entity\TaxPayer\TaxPayer;
use App\Entity\Transaction\RecurrentTransaction;
use DateInterval;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;

class RecurrentTransactionData {
    private ?RecurrentTransaction $entity;

    /**
     * @var string|null
     * @Assert\NotNull()
     * @Assert\Length(min=1, max=64)
     */
    private ?string $name = null;

    /**
     * @var float|null
     * @Assert\NotNull()
     */
    private ?float $total = null;

    /**
     * @var DateTime|null
     * @Assert\NotNull()
     */
    private ?DateTime $startTime = null;

    /**
     * @var DateInterval|null
     * @Assert\NotNull()
     */
    private ?DateInterval $interval = null;

    /**
     * @var TaxPayer|null
     * @Assert\NotNull()
     */
    private ?TaxPayer $taxPayer = null;

    /**
     * @var Category|null
     * @Assert\NotNull()
     */
    private ?Category $category = null;

    /**
     * @var DateTime|null
     */
    private ?DateTime $endTime = null;


    public function __construct(RecurrentTransaction $recurrentTransaction = null) {
        $this->entity = $recurrentTransaction;
    }

    public function getEntity(): ?RecurrentTransaction {
        return $this->entity;
    }

    public function transfer(RecurrentTransaction $recurrentTransaction): void {
        $recurrentTransaction->setName($this->name);
        $recurrentTransaction->setTotal($this->total);
        $recurrentTransaction->setStartTime($this->startTime);
        $recurrentTransaction->setInterval($this->interval);
        $recurrentTransaction->setTaxPayer($this->taxPayer);
        $recurrentTransaction->setCategory($this->category);
        $recurrentTransaction->setEndTime($this->endTime);
    }

    public function reverseTransfer(RecurrentTransaction $recurrentTransaction): void {
        $this->name = $recurrentTransaction->getName();
        $this->total = $recurrentTransaction->getTotal();
        $this->startTime = $recurrentTransaction->getStartTime();
        $this->interval = $recurrentTransaction->getInterval();
        $this->taxPayer = $recurrentTransaction->getTaxPayer();
        $this->category = $recurrentTransaction->getCategory();
        $this->endTime = $recurrentTransaction->getEndTime();
    }

    public function createOrUpdateEntity(): RecurrentTransaction {
        if ($this->entity === null) {
            $this->entity = new RecurrentTransaction($this->name, $this->total, $this->taxPayer, $this->category, $this->startTime, $this->interval, $this->endTime);
        }
        $this->transfer($this->entity);
        return $this->entity;
    }

}
