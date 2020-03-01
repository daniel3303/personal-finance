<?php

namespace App\Entity\Account;

use Carbon\Carbon;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;

class Transfer {
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
}
