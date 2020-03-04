<?php

namespace App\Dto\Category;

use App\Entity\Category\Category;
use App\Entity\User\User;
use Symfony\Component\Validator\Constraints as Assert;

class CategoryData {
    private ?Category $entity = null;

    /**
     * @var User|null
     * @Assert\NotNull()
     */
    private ?User $user = null;

    /**
     * @Assert\NotNull()
     * @Assert\Length(min=1, max=64)
     */
    private ?string $name = null;

    public function __construct(?Category $category = null) {
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

    public function getEntity() : ?Category{
        return $this->entity;
    }

    public function transfer(Category $entity): void {
        $entity->setUser($this->user);
        $entity->setName($this->name);
    }

    public function reverseTransfer(Category $category): void {
        $this->user = $category->getUser();
        $this->name = $category->getName();
    }

    public function createOrUpdateEntity(): Category{
        if($this->entity === null){
            $this->entity = new Category($this->user, $this->name);
        }
        $this->transfer($this->entity);
        return $this->entity;
    }
}
