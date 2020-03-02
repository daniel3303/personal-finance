<?php

namespace App\Dto\Category;

use App\Entity\Tag\Tag;
use Symfony\Component\Validator\Constraints as Assert;

class TagData {
    private ?Tag $entity = null;

    /**
     * @Assert\NotNull()
     * @Assert\Length(min=1, max=64)
     */
    private ?string $name = null;

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
            $this->entity = new Tag($this->name);
        }
        $this->transfer($this->entity);
        return $this->entity;
    }
}
