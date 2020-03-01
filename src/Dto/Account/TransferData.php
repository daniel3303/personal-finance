<?php

namespace App\Dto\Account;

use App\Entity\Account\Account;
use App\Entity\Account\Transfer;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;

class TransferData {
    private ?Transfer $entity = null;

    /**
     * @Assert\NotNull()
     * @Assert\Length(min=1, max=64)
     */
    private ?string $title = null;

    /**
     * @Assert\Type(type="float")
     * @Assert\NotNull()
     */
    private ?float $total = null;

    /**
     * @Assert\NotNull()
     */
    private ?DateTime $time = null;

    /**
     * @Assert\NotNull()
     */
    private ?Account $source = null;

    /**
     * @Assert\NotNull()
     * @Assert\NotEqualTo(propertyPath="source", message="The target account can not be equal to the source account.")
     */
    private ?Account $target = null;

    public function __construct(?Transfer $transfer = null) {
        $this->entity = $transfer;
        if($transfer){
            $this->reverseTransfer($transfer);
        }
    }

    public function getTitle(): ?string {
        return $this->title;
    }

    public function setTitle(?string $title): self {
        $this->title = $title;

        return $this;
    }

    public function getTotal(): ?float {
        return $this->total;
    }

    public function setTotal(?float $total): self {
        $this->total = $total;
        return $this;
    }

    public function getTime(): ?DateTime {
        return $this->time;
    }

    public function setTime(?DateTime $time): self {
        $this->time = $time;

        return $this;
    }

    public function getSource(): ?Account {
        return $this->source;
    }

    public function setSource(?Account $source): self {
        $this->source = $source;

        return $this;
    }

    public function getTarget(): ?Account {
        return $this->target;
    }

    public function setTarget(?Account $target): self {
        $this->target = $target;

        return $this;
    }

    public function getEntity() : ?Transfer{
        return $this->entity;
    }

    public function transfer(Transfer $entity): void {
        $entity->setTitle($this->title);
        $entity->setTotal($this->total);
        $entity->setTime($this->time);
        $entity->setSource($this->source);
        $entity->setTarget($this->target);
    }

    public function reverseTransfer(Transfer $transfer): void {
        $this->title = $transfer->getTitle();
        $this->total = $transfer->getTotal();
        $this->time = $transfer->getTime();
        $this->source = $transfer->getSource();
        $this->target = $transfer->getTarget();
    }

    public function createOrUpdateEntity(): Transfer{
        if($this->entity === null){
            $this->entity = new Transfer($this->title, $this->total, $this->time, $this->source, $this->target);
        }
        $this->transfer($this->entity);

        return $this->entity;
    }
}
