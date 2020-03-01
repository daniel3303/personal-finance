<?php

namespace App\Entity\Account;

use Carbon\Carbon;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Represents a transfer of money between accounts.
 * @ORM\Entity(repositoryClass="App\Repository\Account\TransferRepository")
 */
class Transfer {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private string $title;

    /**
     * @ORM\Column(type="float")
     */
    private float $total;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $time;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account\Account", inversedBy="transfersAsSource")
     */
    private Account $source;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account\Account", inversedBy="transfersAsTarget")
     */
    private Account $target;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $creationTime;


    public function __construct(string $title, float $total, DateTime $time, Account $source, Account $target) {
        $this->title = $title;
        $this->total = $total;
        $this->time = $time;
        $this->source = $source;
        $this->target = $target;
        $this->creationTime = new DateTime();
    }


    public function getId(): ?int {
        return $this->id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function setTitle(string $title): self {
        $this->title = $title;

        return $this;
    }

    public function getTotal(): float {
        return $this->total;
    }

    public function setTotal(float $total): self {
        $this->total = $total;
        return $this;
    }

    public function getTime(): Carbon {
        return Carbon::instance($this->time);
    }

    public function setTime(DateTime $time): self {
        $this->time = $time;

        return $this;
    }

    public function getSource(): ?Account {
        return $this->source;
    }

    public function setSource(Account $source): self {
        if ($this->source !== $source) {
            $this->source->removeTransferAsSource($this);
        }
        $this->source = $source;
        $source->addTransferAsSource($this);

        return $this;
    }

    public function getTarget(): Account {
        return $this->target;
    }

    public function setTarget(Account $target): self {
        if ($this->target !== $target) {
            $this->target->removeTransferAsTarget($this);
        }
        $this->target = $target;
        $target->addTransferAsTarget($this);

        return $this;
    }

    public function getCreationTime(): Carbon {
        return Carbon::instance($this->creationTime);
    }

    public function setCreationTime(DateTime $time): self {
        $this->creationTime = $time;

        return $this;
    }
}
