<?php

namespace App\Entity\Account;

use App\Entity\Transaction\Transaction;
use App\Entity\User\User;
use Carbon\Carbon;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Account\AccountRepository")
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"name", "user_id"})
 * })
 * @ORM\InheritanceType("JOINED")
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
     * @ORM\Column(type="string", length=64)
     */
    private string $name;

    /**
     * @ORM\Column(type="float")
     */
    private float $total;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $initialAmountTime;

    /**
     * @ORM\Column(type="string", length=34, nullable=true)
     */
    private ?string $iban = null;

    /**
     * @ORM\Column(type="text", length=65535, nullable=true)
     */
    private ?string $notes = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $creationTime;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Account\Transfer", mappedBy="source", orphanRemoval=true)
     */
    private Collection $transfersAsSource;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Account\Transfer", mappedBy="target", orphanRemoval=true)
     */
    private Collection $transfersAsTarget;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction\Transaction", mappedBy="account")
     */
    private Collection $transactions;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    public function __construct(User $user, string $name, float $total, DateTime $initialAmountTime) {
        $this->name = $name;
        $this->total = $total;
        $this->initialAmountTime = $initialAmountTime;
        $this->user = $user;
        $this->creationTime = new DateTime();
        $this->transfersAsSource = new ArrayCollection();
        $this->transfersAsTarget = new ArrayCollection();
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

    public function getTotal(): float {
        return $this->total;
    }

    public function setTotal(float $total): self {
        $this->total = $total;

        return $this;
    }

    public function addTotal(float $value): self {
        $this->total += $value;
        return $this;
    }

    public function getNotes(): ?string {
        return $this->notes;
    }

    public function setNotes(?string $notes): self {
        $this->notes = $notes;

        return $this;
    }

    public function getInitialAmountTime(): ?Carbon {
        return Carbon::instance($this->initialAmountTime);
    }

    public function setInitialAmountTime(DateTime $initialAmountTime): self {
        $this->initialAmountTime = $initialAmountTime;

        return $this;
    }

    public function getCreationTime(): Carbon {
        return Carbon::instance($this->creationTime);
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

    /**
     * @return Collection|Transfer[]
     */
    public function getTransfersAsSource(): Collection {
        return $this->transfersAsSource;
    }

    public function addTransferAsSource(Transfer $transfer): self {
        if (!$this->transfersAsSource->contains($transfer)) {
            $this->transfersAsSource[] = $transfer;
            $transfer->setSource($this);

            // Update total
            if ($transfer->getTime()->isAfter($this->getInitialAmountTime())) {
                $this->total -= $transfer->getTotal();
            }
        }

        return $this;
    }

    public function removeTransferAsSource(Transfer $transfer): self {
        if ($this->transfersAsSource->contains($transfer)) {
            $this->transfersAsSource->removeElement($transfer);

            // Update total
            if ($transfer->getTime()->isAfter($this->getInitialAmountTime())) {
                $this->total += $transfer->getTotal();
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transfer[]
     */
    public function getTransfersAsTarget(): Collection {
        return $this->transfersAsTarget;
    }

    public function addTransferAsTarget(Transfer $transfer): self {
        if (!$this->transfersAsTarget->contains($transfer)) {
            $this->transfersAsTarget[] = $transfer;
            $transfer->setTarget($this);

            // Update total
            if ($transfer->getTime()->isAfter($this->getInitialAmountTime())) {
                $this->total += $transfer->getTotal();
            }
        }

        return $this;
    }

    public function removeTransferAsTarget(Transfer $transfer): self {
        if ($this->transfersAsTarget->contains($transfer)) {
            $this->transfersAsTarget->removeElement($transfer);

            // Update total
            if ($transfer->getTime()->isAfter($this->getInitialAmountTime())) {
                $this->total -= $transfer->getTotal();
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactions(): Collection {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setAccount($this);

            // Update total
            if ($transaction->getTime()->isAfter($this->getInitialAmountTime())) {
                $this->total += $transaction->getTotal();
            }
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self {
        if ($this->transactions->contains($transaction)) {
            $this->transactions->removeElement($transaction);

            // Update total
            if ($transaction->getTime()->isAfter($this->getInitialAmountTime())) {
                $this->total -= $transaction->getTotal();
            }
        }

        return $this;
    }

    public function getUser(): User {
        return $this->user;
    }

    public function setUser(User $user): self {
        $this->user = $user;

        return $this;
    }
}
