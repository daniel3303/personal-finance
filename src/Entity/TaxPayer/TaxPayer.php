<?php

namespace App\Entity\TaxPayer;

use App\Entity\Media\Image;
use App\Entity\Transaction\Expense;
use App\Entity\Transaction\Revenue;
use Carbon\Carbon;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
    private bool $enabled = true;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Media\Image", cascade={"persist", "remove"})
     */
    private ?Image $photo = null;

    /**
     * @ORM\Column(type="string", length=128, unique=true)
     * @Assert\NotNull()
     * @Assert\Length(min=1, max=128)
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="string", length=65536, nullable=true)
     * @Assert\Length(max="65536")
     */
    private ?string $description = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $creationTime;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction\Revenue", mappedBy="taxPayer", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private Collection $revenues;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction\Expense", mappedBy="taxPayer", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private Collection $expenses;

    public function __construct() {
        $this->creationTime = new DateTime();
        $this->revenues = new ArrayCollection();
        $this->expenses = new ArrayCollection();
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

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(?string $description): self {
        $this->description = $description;

        return $this;
    }

    public function getCreationTime(): ?Carbon {
        return $this->creationTime !== null ? Carbon::instance($this->creationTime) : null;
    }

    public function setCreationTime(\DateTime $creationTime): self {
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

    public function getEnabled(): ?bool {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return Collection|Revenue[]
     */
    public function getRevenues(): Collection {
        return $this->revenues;
    }

    public function addRevenue(Revenue $revenue): self {
        if (!$this->revenues->contains($revenue)) {
            $this->revenues[] = $revenue;
            $revenue->setTaxPayer($this);
        }

        return $this;
    }

    public function removeRevenue(Revenue $revenue): self {
        if ($this->revenues->contains($revenue)) {
            $this->revenues->removeElement($revenue);
            // set the owning side to null (unless already changed)
            if ($revenue->getTaxPayer() === $this) {
                $revenue->setTaxPayer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Expense[]
     */
    public function getExpenses(): Collection {
        return $this->expenses;
    }

    public function addExpense(Expense $expense): self {
        if (!$this->expenses->contains($expense)) {
            $this->expenses[] = $expense;
            $expense->setTaxPayer($this);
        }

        return $this;
    }

    public function removeExpense(Expense $expense): self {
        if ($this->expenses->contains($expense)) {
            $this->expenses->removeElement($expense);
            // set the owning side to null (unless already changed)
            if ($expense->getTaxPayer() === $this) {
                $expense->setTaxPayer(null);
            }
        }

        return $this;
    }

}
