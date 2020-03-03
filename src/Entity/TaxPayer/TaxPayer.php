<?php

namespace App\Entity\TaxPayer;

use App\Entity\Media\Image;
use App\Entity\Transaction\Revenue;
use App\Entity\Transaction\Transaction;
use Carbon\Carbon;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaxPayer\TaxPayerRepository")
 */
class TaxPayer {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $enabled;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Media\Image", cascade={"persist", "remove"})
     */
    private ?Image $photo = null;

    /**
     * @ORM\Column(type="string", length=128, unique=true)
     */
    private string $name;

    /**
     * Total received from this tax payer minus total spent
     * @ORM\Column(type="float")
     */
    private float $total;

    /**
     * @ORM\Column(type="text", length=65535, nullable=true)
     */
    private ?string $description = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $creationTime;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction\Transaction", mappedBy="taxPayer", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private Collection $transactions;


    public function __construct(bool $enabled, string $name) {
        $this->enabled = $enabled;
        $this->name = $name;
        $this->total = 0;
        $this->creationTime = new DateTime();
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(?string $description): self {
        $this->description = $description;

        return $this;
    }

    public function getCreationTime(): Carbon {
        return Carbon::instance($this->creationTime);
    }

    public function setCreationTime(DateTime $creationTime): self {
        $this->creationTime = $creationTime;

        return $this;
    }

    /**
     * @return Image|null
     */
    public function getPhoto(): ?Image {
        return $this->photo;
    }

    public function setPhoto(?Image $photo): self {
        $this->photo = $photo;
        return $this;
    }

    public function isEnabled(): bool {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return Collection|Revenue[]
     */
    public function getTransactions(): Collection {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setTaxPayer($this);
            $this->total += $transaction->getTotal();
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self {
        if ($this->transactions->contains($transaction)) {
            $this->transactions->removeElement($transaction);
            $this->total -= $transaction->getTotal();
        }

        return $this;
    }

    public function getTotal(): float {
        return $this->total;
    }

    public function setTotal(float $total): self {
        $this->total = $total;
        return $this;
    }

    public function addTotal(float $value) : self {
        $this->total += $value;
        return $this;
    }
}
