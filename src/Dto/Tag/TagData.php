<?php

namespace App\Dto\Tag;

use App\Entity\Tag\Tag;
use App\Entity\User\User;
use Symfony\Component\Validator\Constraints as Assert;

class TagData {
    private ?Tag $entity = null;

    /**
     * @Assert\NotNull()
     * @Assert\Length(min=1, max=64)
     */
    private ?string $name = null;

    /**
     * @Assert\NotNull()
     */
    private ?User $user = null;

    public function __construct(?Tag $category = null) {
        $this->entity = $category;
        if($category){
            $this->reverseTransfer($category);
        }
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(?string $name): self {
        $this->name = $name;

        return $this;
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

    public function getEntity() : ?Tag{
        return $this->entity;
    }

    public function transfer(Tag $entity): void {
        $entity->setName($this->name);
    }

    public function reverseTransfer(Tag $category): void {
        $this->name = $category->getName();
    }

    public function createOrUpdateEntity(): Tag{
        if($this->entity === null){
            $this->entity = new Tag($this->user, $this->name);
        }
        $this->transfer($this->entity);
        return $this->entity;
    }
}
