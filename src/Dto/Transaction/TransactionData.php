<?php

namespace App\Dto\Transaction;

use App\Entity\Account\Account;
use App\Entity\Category\Category;
use App\Entity\Tag\Tag;
use App\Entity\TaxPayer\TaxPayer;
use App\Entity\Transaction\Transaction;
use App\Entity\User\User;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

abstract class TransactionData {

    /**
     * @var User|null
     * @Assert\NotNull()
     */
    private ?User $user = null;

    /**
     * @Assert\Length(max=128)
     */
    private ?string $title = null;

    /**
     * @Assert\NotNull()
     */
    private ?float $total = null;


    /**
     * @Assert\NotNull()
     */
    private ?DateTime $time = null;

    /**
     * @Assert\Length(max=65535)
     */
    private ?string $description = null;

    /**
     * @Assert\NotNull()
     */
    private ?Account $account = null;

    /**
     * @Assert\NotNull()
     */
    private ?TaxPayer $taxPayer = null;

    /**
     * @Assert\NotNull()
     */
    private ?Category $category = null;

    /**
     * @var Collection
     */
    private Collection $tags;

    public function __construct(Transaction $transaction = null) {
        $this->tags = new ArrayCollection();
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void {
        $this->title = $title;
    }

    /**
     * @return float|null
     */
    public function getTotal(): ?float {
        return $this->total;
    }

    /**
     * @param float|null $total
     */
    public function setTotal(?float $total): void {
        $this->total = $total;
    }

    /**
     * @return DateTime|null
     */
    public function getTime(): ?DateTime {
        return $this->time;
    }

    /**
     * @param DateTime|null $time
     */
    public function setTime(?DateTime $time): void {
        $this->time = $time;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void {
        $this->description = $description;
    }

    /**
     * @return Account|null
     */
    public function getAccount(): ?Account {
        return $this->account;
    }

    /**
     * @param Account|null $account
     */
    public function setAccount(?Account $account): void {
        $this->account = $account;
    }

    /**
     * @return TaxPayer|null
     */
    public function getTaxPayer(): ?TaxPayer {
        return $this->taxPayer;
    }

    /**
     * @param TaxPayer|null $taxPayer
     */
    public function setTaxPayer(?TaxPayer $taxPayer): void {
        $this->taxPayer = $taxPayer;
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category {
        return $this->category;
    }

    /**
     * @param Category|null $category
     */
    public function setCategory(?Category $category): void {
        $this->category = $category;
    }

    /**
     * @return Collection
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

    /**
     * @return User|null
     */
    public function getUser(): ?User {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(?User $user): void {
        $this->user = $user;
    }

    protected function transactionTransfer(Transaction $transaction): void {
        $transaction->setUser($this->user);
        $transaction->setTitle($this->title);
        $transaction->setTotal($this->total);
        $transaction->setDescription($this->description);
        $transaction->setAccount($this->account);
        $transaction->setTaxPayer($this->taxPayer);
        $transaction->setTime($this->time);
        $transaction->setCategory($this->category);

        // Add new tags
        foreach ($this->tags as $tag) {
            if (!$transaction->getTags()->contains($tag)) {
                $transaction->addTag($tag);
            }
        }

        // Remove old tags
        foreach ($transaction->getTags() as $tag) {
            if (!$this->tags->contains($tag)) {
                $transaction->removeTag($tag);
            }
        }
    }

    protected function transactionReverseTransfer(Transaction $transaction): void {
        $this->user = $transaction->getUser();
        $this->title = $transaction->getTitle();
        $this->total = $transaction->getTotal();
        $this->description = $transaction->getDescription();
        $this->account = $transaction->getAccount();
        $this->taxPayer = $transaction->getTaxPayer();
        $this->time = $transaction->getTime();
        $this->category = $transaction->getCategory();

        $this->tags->clear();
        foreach ($transaction->getTags() as $tag) {
            $this->addTag($tag);
        }
    }

}
