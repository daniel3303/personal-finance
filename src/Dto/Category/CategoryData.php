<?php

namespace App\Dto\Category;

use App\Entity\Category\Category;
use App\Entity\Media\Image;
use App\Entity\TaxPayer\TaxPayer;
use Metadata\Tests\Driver\Fixture\C\SubDir\C;
use Symfony\Component\Validator\Constraints as Assert;

class CategoryData {
    private ?Category $entity = null;

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

    public function getEntity() : ?Category{
        return $this->entity;
    }

    public function transfer(Category $entity): void {
        $entity->setName($this->name);
    }

    public function reverseTransfer(Category $category): void {
        $this->name = $category->getName();
    }

    public function createOrUpdateEntity(): Category{
        if($this->entity === null){
            $this->entity = new Category($this->name);
        }
        $this->transfer($this->entity);
        return $this->entity;
    }
}
