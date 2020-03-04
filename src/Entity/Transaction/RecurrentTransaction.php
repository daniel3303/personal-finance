<?php

namespace App\Entity\Transaction;

use App\Entity\Account\AssetAccount;
use App\Entity\Category\Category;
use App\Entity\Tag\Tag;
use App\Entity\TaxPayer\TaxPayer;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use DateInterval;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transaction\RecurrentTransactionRepository")
 */
class RecurrentTransaction {
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
     * @ORM\Column(type="dateinterval")
     */
    private DateInterval $interval;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTime $endTime;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag\Tag")
     */
    private Collection $tags;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $creationTime;

    public function __construct(string $name, float $total, AssetAccount $account, TaxPayer $taxPayer,
                                Category $category, DateTime $startTime,
                                DateInterval $interval, ?DateTime $endTime = null) {

        $this->title = $name;
        $this->total = $total;
        $this->account = $account;
        $this->taxPayer = $taxPayer;
        $this->category = $category;
        $this->startTime = $startTime;
        $this->interval = $interval;
        $this->endTime = $endTime;
        $this->tags = new ArrayCollection();
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

    public function getEndTime(): Carbon {
        return Carbon::instance($this->endTime);
    }

    public function setEndTime(DateTime $endTime): self {
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
}
