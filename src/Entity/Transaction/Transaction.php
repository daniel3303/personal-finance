<?php

namespace App\Entity\Transaction;

use App\Entity\Account\Account;
use App\Entity\TaxPayer\TaxPayer;
use Carbon\Carbon;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use DoctrineExtensions\Query\Mysql\Date;
use JMS\Serializer\Tests\Fixtures\Discriminator\Car;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Represents a exchange of goods between you and a tax payer. Usually you provide a service and
 * get paid for it or you buy something or some service and pay for it.
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

    public function __construct(float $total, DateTime $time, Account $account, TaxPayer $taxPayer) {
        $this->total = $total;
        $this->time = $time;
        $this->account = $account;
        $this->taxPayer = $taxPayer;
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
        $olderTaxPayer = $this->taxPayer;
        $this->taxPayer = $taxPayer;

        if ($olderTaxPayer && $olderTaxPayer !== $taxPayer) {
            $olderTaxPayer->removeTransaction($this);
        }
        if ($taxPayer) {
            $taxPayer->addTransaction($this);
        }
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
        $oldAccount = $this->account;
        $this->account = $account;

        if ($oldAccount && $oldAccount !== $account) {
            $oldAccount->removeTransaction($this);
        }
        if ($account) {
            $account->addTransaction($this);
        }
        return $this;
    }
}
