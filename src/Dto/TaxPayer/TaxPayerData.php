<?php

namespace App\Dto\TaxPayer;

use App\Entity\Media\Image;
use App\Entity\TaxPayer\TaxPayer;
use Symfony\Component\Validator\Constraints as Assert;

class TaxPayerData {
    private ?TaxPayer $entity;

    /**
     * @var bool
     * @Assert\NotNull()
     */
    private ?bool $enabled;

    /**
     * @var Image|null
     */
    private ?Image $photo = null;

    /**
     * @Assert\NotNull()
     * @Assert\Length(min=1, max=128)
     */
    private ?string $name;

    /**
     * @Assert\Length(max="65535")
     */
    private ?string $description = null;

    public function __construct(?TaxPayer $taxPayer = null) {
        $this->entity = $taxPayer;
    }


    public function getName(): ?string {
        return $this->name;
    }

    public function setName(?string $name): self {
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

    public function setEnabled(?bool $enabled): self {
        $this->enabled = $enabled;

        return $this;
    }

    public function createOrUpdateEntity(): TaxPayer{
        if($this->entity === null){
            $this->entity = new TaxPayer($this->enabled, $this->name);
        }
        $this->entity->setEnabled($this->enabled);
        $this->entity->setName($this->name);
        $this->entity->setPhoto($this->photo);
        $this->entity->setDescription($this->description);

        return $this->entity;
    }
}
