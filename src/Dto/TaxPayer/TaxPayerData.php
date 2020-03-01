<?php

namespace App\Dto\TaxPayer;

use App\Entity\Media\Image;
use Symfony\Component\Validator\Constraints as Assert;

class TaxPayerData {

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
}
