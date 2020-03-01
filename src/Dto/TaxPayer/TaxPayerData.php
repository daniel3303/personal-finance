<?php

namespace App\Dto\TaxPayer;

use App\Entity\Media\Image;
use App\Entity\TaxPayer\TaxPayer;
use App\Entity\Transaction\Transaction;
use Symfony\Component\Validator\Constraints as Assert;

class TaxPayerData {
    private ?TaxPayer $entity = null;

    /**
     * @var bool
     * @Assert\NotNull()
     */
    private ?bool $enabled = null;

    /**
     * @var Image|null
     */
    private ?Image $photo = null;

    /**
     * @Assert\NotNull()
     * @Assert\Length(min=1, max=128)
     */
    private ?string $name = null;

    /**
     * @Assert\Length(max="65535")
     */
    private ?string $description = null;

    public function __construct(?TaxPayer $taxPayer = null) {
        $this->entity = $taxPayer;
        if($taxPayer){
            $this->reverseTransfer($taxPayer);
        }
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

    public function getEntity() : ?TaxPayer{
        return $this->entity;
    }

    public function transfer(TaxPayer $entity): void {
        $entity->setEnabled($this->enabled);
        $entity->setName($this->name);
        $entity->setPhoto($this->photo);
        $entity->setDescription($this->description);
    }

    public function reverseTransfer(TaxPayer $taxPayer): void {
        $this->enabled = $taxPayer->isEnabled();
        $this->photo = $taxPayer->getPhoto();
        $this->name = $taxPayer->getName();
        $this->description = $taxPayer->getDescription();
    }

    public function createOrUpdateEntity(): TaxPayer{
        if($this->entity === null){
            $this->entity = new TaxPayer($this->enabled, $this->name);
        }
        $this->transfer($this->entity);
        return $this->entity;
    }
}
