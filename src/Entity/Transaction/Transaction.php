<?php

namespace App\Entity\Transaction;

use App\Entity\TaxPayer\TaxPayer;
use Carbon\Carbon;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use DoctrineExtensions\Query\Mysql\Date;
use JMS\Serializer\Tests\Fixtures\Discriminator\Car;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Transaction\TransactionRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorMap({
 *     "expense" = "Expense",
 *     "revenue" = "Revenue",
 *     "indebtedness" = "Indebtedness",
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
     * @Assert\Length(max=128)
     */
    private ?string $title = null;

    /**
     * @ORM\Column(type="float")
     */
    private float $total = 0;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $creationTime;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotNull()
     */
    private ?DateTime $time = null;

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

    public function getTotal(): float {
        return $this->total;
    }

    public function setTotal(float $total): self {
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

    public function getTime(): ?Carbon {
        return $this->time !== null ? Carbon::instance($this->time) : null;
    }

    public function setTime(DateTime $time): self {
        $this->time = $time;

        return $this;
    }

    /**
     * @return TaxPayer|null
     */
    abstract public function getTaxPayer(): ?TaxPayer;

    /**
     * @param TaxPayer|null $taxPayer
     * @return mixed
     */
    abstract public function setTaxPayer(?TaxPayer $taxPayer);

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(?string $description): self {
        $this->description = $description;

        return $this;
    }
}
