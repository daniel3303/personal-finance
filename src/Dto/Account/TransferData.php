<?php

namespace App\Dto\Account;

use App\Entity\Account\Account;
use App\Entity\Account\Transfer;
use DateTime;
use Symfony\Component\Validator\Constraints as Assert;

class TransferData {
    private ?Transfer $entity;

    /**
     * @Assert\NotNull()
     * @Assert\Length(min=1, max=64)
     */
    private ?string $title;

    /**
     * @Assert\Type(type="float")
     * @Assert\NotNull()
     */
    private ?float $total;

    /**
     * @Assert\NotNull()
     */
    private ?DateTime $time;

    /**
     * @Assert\NotNull()
     */
    private ?Account $source;

    /**
     * @Assert\NotNull()
     * @Assert\NotEqualTo(propertyPath="target", message="The target account can not be equal to the source account.")
     */
    private ?Account $target;

    public function __construct(?Transfer $transfer = null) {
        $this->entity = $transfer;
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

    public function createOrUpdateEntity(): Transfer{
        if($this->entity === null){
            $this->entity = new Transfer($this->title, $this->total, $this->time, $this->source, $this->target);
        }
        $this->entity->setTitle($this->title);
        $this->entity->setTotal($this->total);
        $this->entity->setTime($this->time);
        $this->entity->setSource($this->source);
        $this->entity->setTarget($this->target);

        return $this->entity;
    }
}
