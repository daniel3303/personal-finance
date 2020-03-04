<?php

namespace App\Entity\Transaction;

use App\Entity\Category\Category;
use App\Entity\TaxPayer\TaxPayer;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use DateInterval;
use DateTime;
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
    private string $name;

    /**
     * @ORM\Column(type="float")
     */
    private float $total;

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
    private DateTime $endTime;

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
    private DateTime $creationTime;

    public function __construct() {
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

    public function getCreationTime(): Carbon {
        return Carbon::instance($this->creationTime);
    }

    public function setCreationTime(DateTime $creationTime): self {
        $this->creationTime = $creationTime;

        return $this;
    }
}
