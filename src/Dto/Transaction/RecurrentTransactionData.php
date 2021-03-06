<?php

namespace App\Dto\Transaction;

use App\Entity\Account\AssetAccount;
use App\Entity\Category\Category;
use App\Entity\Tag\Tag;
use App\Entity\TaxPayer\TaxPayer;
use App\Entity\Transaction\Recurrent\RecurrentTransaction;
use App\Entity\User\User;
use Carbon\CarbonInterval;
use DateInterval;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class RecurrentTransactionData {
    private ?RecurrentTransaction $entity;

    /**
     * @var User|null
     * @Assert\NotNull()
     */
    private ?User $user = null;

    /**
     * @var bool|null
     * @Assert\NotNull()
     */
    private ?bool $enabled = null;

    /**
     * @var string|null
     * @Assert\NotNull()
     * @Assert\Length(min=1, max=64)
     */
    private ?string $title = null;

    /**
     * @var float|null
     * @Assert\NotNull()
     */
    private ?float $total = null;

    /**
     * @var DateTime|null
     * @Assert\NotNull()
     */
    private ?DateTime $startTime = null;

    /**
     * @var DateInterval|null
     * @Assert\NotNull()
     */
    private ?DateInterval $interval = null;

    /**
     * @var AssetAccount|null
     * @Assert\NotNull()
     */
    private ?AssetAccount $account = null;

    /**
     * @var TaxPayer|null
     * @Assert\NotNull()
     */
    private ?TaxPayer $taxPayer = null;

    /**
     * @var Category|null
     * @Assert\NotNull()
     */
    private ?Category $category = null;

    /**
     * @var DateTime|null
     */
    private ?DateTime $endTime = null;

    /**
     * @var Collection
     */
    private Collection $tags;


    public function __construct(RecurrentTransaction $recurrentTransaction = null) {
        $this->tags = new ArrayCollection();
        $this->entity = $recurrentTransaction;
        if($recurrentTransaction !== null){
            $this->reverseTransfer($recurrentTransaction);
        }
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
    public function getStartTime(): ?DateTime {
        return $this->startTime;
    }

    /**
     * @param DateTime|null $startTime
     */
    public function setStartTime(?DateTime $startTime): void {
        $this->startTime = $startTime;
    }

    /**
     * @return DateInterval|null
     */
    public function getInterval(): ?DateInterval {
        return $this->interval;
    }

    /**
     * @param DateInterval|null $interval
     */
    public function setInterval(?DateInterval $interval): void {
        $this->interval = $interval;
    }

    /**
     * @return AssetAccount|null
     */
    public function getAccount(): ?AssetAccount {
        return $this->account;
    }

    /**
     * @param AssetAccount|null $account
     */
    public function setAccount(?AssetAccount $account): void {
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
     * @return DateTime|null
     */
    public function getEndTime(): ?DateTime {
        return $this->endTime;
    }

    /**
     * @param DateTime|null $endTime
     */
    public function setEndTime(?DateTime $endTime): void {
        $this->endTime = $endTime;
    }

    /**
     * @return Collection
     */
    public function getTags(): Collection {
        return $this->tags;
    }

    public function addTag(Tag $tag) : void {
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


    public function getEntity(): ?RecurrentTransaction {
        return $this->entity;
    }

    /**
     * @return bool|null
     */
    public function isEnabled(): ?bool {
        return $this->enabled;
    }

    /**
     * @param bool|null $enabled
     */
    public function setEnabled(?bool $enabled): void {
        $this->enabled = $enabled;
    }



    public function transfer(RecurrentTransaction $recurrentTransaction): void {
        $recurrentTransaction->setUser($this->user);
        $recurrentTransaction->setEnabled($this->enabled);
        $recurrentTransaction->setTitle($this->title);
        $recurrentTransaction->setTotal($this->total);
        $recurrentTransaction->setStartTime($this->startTime);
        $recurrentTransaction->setInterval($this->interval);
        $recurrentTransaction->setAccount($this->account);
        $recurrentTransaction->setTaxPayer($this->taxPayer);
        $recurrentTransaction->setCategory($this->category);
        $recurrentTransaction->setEndTime($this->endTime);

        // Add new tags
        foreach ($this->tags as $tag){
            if(!$recurrentTransaction->getTags()->contains($tag)){
                $recurrentTransaction->addTag($tag);
            }
        }

        // Remove old tags
        foreach ($recurrentTransaction->getTags() as $tag){
            if(!$this->tags->contains($tag)){
                $recurrentTransaction->removeTag($tag);
            }
        }
    }

    public function reverseTransfer(RecurrentTransaction $recurrentTransaction): void {
        $this->user = $recurrentTransaction->getUser();
        $this->enabled = $recurrentTransaction->isEnabled();
        $this->title = $recurrentTransaction->getTitle();
        $this->total = $recurrentTransaction->getTotal();
        $this->startTime = $recurrentTransaction->getStartTime();
        $this->interval = $recurrentTransaction->getInterval();
        $this->account = $recurrentTransaction->getAccount();
        $this->taxPayer = $recurrentTransaction->getTaxPayer();
        $this->category = $recurrentTransaction->getCategory();
        $this->endTime = $recurrentTransaction->getEndTime();

        $this->tags->clear();
        foreach ($recurrentTransaction->getTags() as $tag){
            $this->addTag($tag);
        }
    }

    public function createOrUpdateEntity(): RecurrentTransaction {
        if ($this->entity === null) {
            $this->entity = new RecurrentTransaction($this->user, $this->enabled, $this->title, $this->total, $this->account, $this->taxPayer, $this->category, $this->startTime, $this->interval, $this->endTime);
        }
        $this->transfer($this->entity);
        return $this->entity;
    }

    /**
     * @param RecurrentTransactionData $recurrentTransactionData
     * @param ExecutionContextInterface $context
     * @param $payload
     * @Assert\Callback()
     */
    public static function validate(RecurrentTransactionData $recurrentTransactionData, ExecutionContextInterface $context, $payload): void {
        // Revenue total must be equal or greater than 0
        $interval = $recurrentTransactionData->getInterval();

        // The interval should be equal or greater than one day
        if ($interval && CarbonInterval::instance($interval)->lessThan(CarbonInterval::day())) {
            $context->buildViolation('The interval must be equal or greater than one day.')
                ->atPath('interval')
                ->addViolation();
        }
    }

}
