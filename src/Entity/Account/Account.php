<?php

namespace App\Entity\Account;

use Carbon\Carbon;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Account\AccountRepository")
 * @ORM\DiscriminatorMap({
 *     "assetAccount" = "AssetAccount",
 *     "liabilityAccount" = "LiabilityAccount"
 * })
 */
abstract class Account {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=64, unique=true)
     * @Assert\Unique()
     * @Assert\NotNull()
     * @Assert\Length(min=1, max=64)
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="float")
     */
    private float $total = 0;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotNull()
     */
    private ?DateTime $initialAmountTime = null;

    /**
     * @ORM\Column(type="string", length=34, nullable=true)
     * @Assert\Length(min=34, max=34)
     */
    private ?string $iban = null;

    /**
     * @ORM\Column(type="string", length=65536, nullable=true)
     */
    private ?string $notes = null;

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

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    public function getTotal(): float {
        return $this->total;
    }

    public function setTotal(float $total): self {
        $this->total = $total;

        return $this;
    }

    public function getNotes(): ?string {
        return $this->notes;
    }

    public function setNotes(string $notes): self {
        $this->notes = $notes;

        return $this;
    }

    public function getInitialAmountTime(): ?Carbon {
        return $this->initialAmountTime !== null ? Carbon::instance($this->initialAmountTime) : null;
    }

    public function setInitialAmountTime(DateTime $initialAmountTime): self {
        $this->initialAmountTime = $initialAmountTime;

        return $this;
    }

    public function getCreationTime(): ?Carbon {
        return $this->creationTime !== null ? Carbon::instance($this->creationTime) : null;
    }

    public function setCreationTime(DateTime $creationTime): self {
        $this->creationTime = $creationTime;

        return $this;
    }

    public function getIban(): ?string {
        return $this->iban;
    }

    public function setIban(?string $iban): self {
        $this->iban = $iban;

        return $this;
    }
}
