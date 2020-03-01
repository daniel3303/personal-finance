<?php

namespace App\Entity\Account;

use Carbon\Carbon;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
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
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private ?string $title = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTime $time = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account\Account", inversedBy="transfersAsSource")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Account $source = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account\Account", inversedBy="transfersAsTarget")
     */
    private ?Account $target = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $creationTime;

    public function __construct() {
        $this->creationTime = new DateTime();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getTitle(): ?string {
        return $this->title;
    }

    public function setTitle(string $title): self {
        $this->title = $title;

        return $this;
    }

    public function getTime(): ?Carbon {
        return $this->time !== null ? Carbon::instance($this->time) : null;
    }

    public function setTime(DateTime $time): self {
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

    public function getCreationTime(): ?Carbon {
        return $this->creationTime !== null ? Carbon::instance($this->creationTime) : null;
    }

    public function setCreationTime(DateTime $time): self {
        $this->creationTime = $time;

        return $this;
    }
}
