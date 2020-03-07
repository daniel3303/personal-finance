<?php

namespace App\Entity\Transaction\Recurrent;

use App\Entity\Account\AssetAccount;
use App\Entity\Category\Category;
use App\Entity\Tag\Tag;
use App\Entity\TaxPayer\TaxPayer;
use App\Entity\Transaction\Expense;
use App\Entity\Transaction\Revenue;
use App\Entity\User\User;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use DateInterval;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transaction\Recurrent\RecurrentTransactionRepository")
 */
class RecurrentTransaction {
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
     * @ORM\Column(type="string", length=64)
     */
    private string $title;

    /**
     * The value of this recurrent transaction. Should be a positive number if the
     * tax payer is putting money into your account. Should be a negative value if you
     * are paying to the tax payer.
     * @ORM\Column(type="float")
     */
    private float $total;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account\AssetAccount")
     * @ORM\JoinColumn(nullable=false)
     */
    private AssetAccount $account;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TaxPayer\TaxPayer")
     * @ORM\JoinColumn(nullable=false)
     */
    private TaxPayer $taxPayer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category\Category")
     * @ORM\JoinColumn(nullable=false)
     */
    private Category $category;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $startTime;

    /**
     * @ORM\Column(type="dateinterval", name="`interval`")
     */
    private DateInterval $interval;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $endTime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $lastTransactionCreationTime = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private DateTime $nextTransactionCreationTime;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag\Tag")
     */
    private Collection $tags;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $creationTime;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private User $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction\Recurrent\RecurrentExpense", mappedBy="recurrentTransaction", cascade={"persist", "remove"})
     */
    private Collection $expenses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction\Recurrent\RecurrentRevenue", mappedBy="recurrentTransaction", cascade={"persist", "remove"})
     */
    private Collection $revenues;

    public function __construct(User $user, bool $enabled, string $name, float $total, AssetAccount $account, TaxPayer $taxPayer,
                                Category $category, DateTime $startTime,
                                DateInterval $interval, ?DateTime $endTime = null) {

        $this->user = $user;
        $this->enabled = $enabled;
        $this->title = $name;
        $this->total = $total;
        $this->account = $account;
        $this->taxPayer = $taxPayer;
        $this->category = $category;
        $this->startTime = $startTime;
        $this->nextTransactionCreationTime = $startTime;
        $this->interval = $interval;
        $this->endTime = $endTime;
        $this->tags = new ArrayCollection();
        $this->expenses = new ArrayCollection();
        $this->revenues = new ArrayCollection();
        $this->creationTime = new DateTime();
        $this->createTransactions();
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

    public function getStartTime(): Carbon {
        return Carbon::instance($this->startTime);
    }

    public function setStartTime(DateTime $startTime): self {
        $this->startTime = $startTime;

        return $this;
    }

    public function getInterval(): CarbonInterval {
        return CarbonInterval::instance($this->interval);
    }

    public function setInterval(DateInterval $interval): self {
        $this->interval = $interval;

        return $this;
    }

    public function getEndTime(): ?Carbon {
        return $this->endTime ? Carbon::instance($this->endTime) : null;
    }

    public function setEndTime(?DateTime $endTime): self {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * @return AssetAccount
     */
    public function getAccount(): AssetAccount {
        return $this->account;
    }

    /**
     * @param AssetAccount $account
     * @return RecurrentTransaction
     */
    public function setAccount(AssetAccount $account): self {
        $this->account = $account;

        return $this;
    }


    public function getTaxPayer(): TaxPayer {
        return $this->taxPayer;
    }

    public function setTaxPayer(TaxPayer $taxPayer): self {
        $this->taxPayer = $taxPayer;

        return $this;
    }

    public function getCategory(): Category {
        return $this->category;
    }

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

    public function getCreationTime(): Carbon {
        return Carbon::instance($this->creationTime);
    }

    public function setCreationTime(DateTime $creationTime): self {
        $this->creationTime = $creationTime;

        return $this;
    }

    public function getNextCreationTime(): Carbon {
        return Carbon::instance($this->nextTransactionCreationTime);
    }

    public function getUser(): User {
        return $this->user;
    }

    public function setUser(User $user): self {
        $this->user = $user;

        return $this;
    }

    public function isEnabled(): bool {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self {
        $this->enabled = $enabled;

        return $this;
    }

    public function getLastTransactionCreationTime(): ?Carbon {
        return $this->lastTransactionCreationTime !== null ? Carbon::instance($this->lastTransactionCreationTime) : null;
    }

    protected function createTransaction(DateTime $time): void {
        if ($this->total >= 0) {
            $transaction = new RecurrentRevenue($this->user, $this->title, $this->total, $time, $this->account, $this->taxPayer, $this->category, $this);
            $this->revenues->add($transaction);
        } else {
            $transaction = new RecurrentExpense($this->user, $this->title, $this->total, $time, $this->account, $this->taxPayer, $this->category, $this);
            $this->expenses->add($transaction);
        }
    }

    public function createTransactions(): void {
        if($this->enabled === false){
            return;
        }
        if ($this->lastTransactionCreationTime === null) {
            $this->lastTransactionCreationTime = $this->getStartTime();
            $this->nextTransactionCreationTime = $this->getStartTime()->add($this->interval);
            $this->createTransaction($this->getLastTransactionCreationTime());
        }

        $period = new CarbonPeriod($this->lastTransactionCreationTime, Carbon::now(), $this->interval);
        $period->excludeStartDate(true);

        foreach ($period as $date) {
            $this->createTransaction(Carbon::instance($date));
            $this->lastTransactionCreationTime = Carbon::instance($date);
            $this->nextTransactionCreationTime = (Carbon::instance($date))->add($this->interval);
        }
    }

    /**
     * @return Collection|RecurrentExpense[]
     */
    public function getExpenses(): Collection {
        return $this->expenses;
    }

    public function removeExpense(RecurrentExpense $expense): self {
        if ($this->expenses->contains($expense)) {
            $this->expenses->removeElement($expense);
        }

        return $this;
    }

    /**
     * @return Collection|RecurrentRevenue[]
     */
    public function getRevenues(): Collection {
        return $this->revenues;
    }

    public function removeRevenue(RecurrentRevenue $revenue): self {
        if ($this->revenues->contains($revenue)) {
            $this->revenues->removeElement($revenue);
        }

        return $this;
    }
}
