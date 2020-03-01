<?php

namespace App\Entity\Transaction;

use App\Entity\TaxPayer\TaxPayer;
use Carbon\Carbon;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use DoctrineExtensions\Query\Mysql\Date;
use JMS\Serializer\Tests\Fixtures\Discriminator\Car;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transaction\TransactionRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorMap({
 *     "expense" = "Expense",
 *     "revenue" = "Revenue"
 * })
 */
abstract class Transaction {
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
     * @ORM\Column(type="decimal", precision=18, scale=2)
     */
    private float $total = 0;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $creationTime;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTime $date = null;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TaxPayer\TaxPayer", inversedBy="trasactions")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?TaxPayer $taxPayer = null;

    /**
     * @ORM\Column(type="string", length=65536, nullable=true)
     */
    private ?string $description = null;

    public function __construct() {
        $this->creationTime = new DateTime();
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

    public function getTotal(): ?string {
        return $this->total;
    }

    public function setTotal(string $total): self {
        $this->total = $total;

        return $this;
    }

    public function getCreationTime(): ?Carbon {
        return $this->creationTime !== null ? Carbon::instance($this->creationTime) : null;
    }

    public function setCreationTime(DateTime $creationTime): self {
        $this->creationTime = $creationTime;

        return $this;
    }

    public function getDate(): ?Carbon {
        return $this->date !== null ? Carbon::instance($this->date) : null;
    }

    public function setDate(DateTime $date): self {
        $this->date = $date;

        return $this;
    }

    public function getTaxPayer(): ?TaxPayer {
        return $this->taxPayer;
    }

    public function setTaxPayer(?TaxPayer $taxPayer): self {
        $this->taxPayer = $taxPayer;

        return $this;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(?string $description): self {
        $this->description = $description;

        return $this;
    }
}
