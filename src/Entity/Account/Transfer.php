<?php

namespace App\Entity\Account;

use App\Entity\Tag\Tag;
use App\Entity\Tag\TaggableInterface;
use App\Entity\User\User;
use Carbon\Carbon;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Represents a transfer of money between accounts.
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="App\Repository\Account\TransferRepository")
 */
class Transfer implements TaggableInterface {
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
     * @ORM\Column(type="float")
     */
    private float $total;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $time;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account\Account", inversedBy="transfersAsSource")
     */
    private Account $source;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account\Account", inversedBy="transfersAsTarget")
     */
    private Account $target;

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


    public function __construct(User $user, string $title, float $total, DateTime $time, Account $source, Account $target) {
        $this->user = $user;
        $this->title = $title;
        $this->total = $total;
        $this->time = $time;
        $this->source = $source;
        $this->target = $target;
        $this->tags = new ArrayCollection();
        $this->creationTime = new DateTime();

        // Notify the accounts about the transfer
        $this->source->addTransferAsSource($this);
        $this->target->addTransferAsTarget($this);
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
        $diff = $total - $this->total;
        $this->total = $total;
        $this->source->addTotal(-$diff);
        $this->target->addTotal($diff);
        return $this;
    }

    public function getTime(): Carbon {
        return Carbon::instance($this->time);
    }

    public function setTime(DateTime $time): self {
        $this->time = $time;

        return $this;
    }

    public function getSource(): ?Account {
        return $this->source;
    }

    public function setSource(Account $source): self {
        if ($this->source !== $source) {
            $this->source->removeTransferAsSource($this);
        }
        $this->source = $source;
        $source->addTransferAsSource($this);

        return $this;
    }

    public function getTarget(): Account {
        return $this->target;
    }

    public function setTarget(Account $target): self {
        if ($this->target !== $target) {
            $this->target->removeTransferAsTarget($this);
        }
        $this->target = $target;
        $target->addTransferAsTarget($this);

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

    public function setCreationTime(DateTime $time): self {
        $this->creationTime = $time;

        return $this;
    }

    /** @ORM\PreRemove() */
    public function onRemove(): void {
        $this->source->removeTransferAsSource($this);
        $this->target->removeTransferAsTarget($this);
    }

    public function getUser(): User {
        return $this->user;
    }

    public function setUser(User $user): self {
        $this->user = $user;

        return $this;
    }
}
