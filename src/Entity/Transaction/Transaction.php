<?php

namespace App\Entity\Transaction;

use App\Entity\Account\Account;
use App\Entity\Category\Category;
use App\Entity\Tag\Tag;
use App\Entity\Tag\TaggableInterface;
use App\Entity\TaxPayer\TaxPayer;
use App\Entity\User\User;
use Carbon\Carbon;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a exchange of goods between you and a tax payer. Usually you provide a service and
 * get paid for it or you buy something or some service and pay for it.
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="App\Repository\Transaction\TransactionRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorMap({
 *     "expense" = "Expense",
 *     "revenue" = "Revenue",
 *     "indebtedness" = "Indebtedness",
 *     "recurrentRevenue" = "App\Entity\Transaction\Recurrent\RecurrentRevenue",
 *     "recurrentExpense" = "App\Entity\Transaction\Recurrent\RecurrentExpense",
 * })
 */
abstract class Transaction implements TaggableInterface {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private ?string $title = null;

    /**
     * @ORM\Column(type="float")
     */
    private float $total;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $time;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $creationTime;

    /**
     * @ORM\Column(type="text", length=65535, nullable=true)
     */
    private ?string $description = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account\Account", inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private Account $account;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TaxPayer\TaxPayer", inversedBy="transactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private TaxPayer $taxPayer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category\Category")
     * @ORM\JoinColumn(nullable=false)
     */
    private Category $category;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag\Tag")
     */
    private Collection $tags;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    public function __construct(User $user, string $title, float $total, DateTime $time, Account $account, TaxPayer $taxPayer, Category $category) {
        $this->user = $user;
        $this->title = $title;
        $this->total = $total;
        $this->time = $time;
        $this->account = $account;
        $this->taxPayer = $taxPayer;
        $this->category = $category;
        $this->creationTime = new DateTime();
        $this->tags = new ArrayCollection();
        $this->taxPayer->addTransaction($this);
        $this->account->addTransaction($this);
    }


    public function getId(): ?int {
        return $this->id;
    }

    public function getTitle(): ?string {
        return $this->title;
    }

    public function setTitle(?string $title): self {
        $this->title = $title;

        return $this;
    }

    public function getTotal(): float {
        return $this->total;
    }

    public function setTotal(float $total): self {
        $diff = $total - $this->total;
        $this->total = $total;

        $this->taxPayer->addTotal($diff);
        $this->account->addTotal($diff);

        return $this;
    }

    public function getCreationTime(): Carbon {
        return Carbon::instance($this->creationTime);
    }

    public function setCreationTime(DateTime $creationTime): self {
        $this->creationTime = $creationTime;

        return $this;
    }

    public function getTime(): Carbon {
        return Carbon::instance($this->time);
    }

    public function setTime(DateTime $time): self {
        $this->time = $time;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getTaxPayer(): TaxPayer {
        return $this->taxPayer;
    }

    /**
     * @inheritDoc
     */
    public function setTaxPayer(TaxPayer $taxPayer): self {
        if ($this->taxPayer !== $taxPayer) {
            $this->taxPayer->removeTransaction($this);
        }
        $this->taxPayer = $taxPayer;
        $taxPayer->addTransaction($this);

        return $this;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(?string $description): self {
        $this->description = $description;

        return $this;
    }

    public function getAccount(): Account {
        return $this->account;
    }

    public function setAccount(Account $account): self {
        if ($this->account !== $account) {
            $this->account->removeTransaction($this);
        }
        $this->account = $account;
        $account->addTransaction($this);

        return $this;
    }

    /**
     * @return Category
     */
    public function getCategory(): Category {
        return $this->category;
    }

    /**
     * @param Category $category
     * @return Transaction
     */
    public function setCategory(Category $category): self {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection {
        return $this->tags;
    }

    public function addTag(Tag $tag): void {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }
    }

    public function removeTag(Tag $tag): void {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }
    }

    public function getUser(): User {
        return $this->user;
    }

    public function setUser(User $user): self {
        $this->user = $user;

        return $this;
    }

    /** @ORM\PreRemove() */
    public function onRemove(): void {
        $this->account->removeTransaction($this);
        $this->taxPayer->removeTransaction($this);
    }

}
