<?php

namespace App\Entity\TaxPayer;

use App\Entity\Media\Image;
use Carbon\Carbon;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaxPayer\TaxPayerRepository")
 */
class TaxPayer {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $enabled = true;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Media\Image", cascade={"persist", "remove"})
     */
    private ?Image $photo = null;

    /**
     * @ORM\Column(type="string", length=128, unique=true)
     * @Assert\NotNull()
     * @Assert\Length(min=1, max=128)
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="string", length=65536, nullable=true)
     * @Assert\Length(max="65536")
     */
    private ?string $description = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $creationTime;

    public function __construct() {
        $this->creationTime = new DateTime();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): self {
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

    public function getCreationTime(): ?Carbon {
        return $this->creationTime !== null ? Carbon::instance($this->creationTime) : null;
    }

    public function setCreationTime(\DateTime $creationTime): self {
        $this->creationTime = $creationTime;

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

    public function setEnabled(bool $enabled): self {
        $this->enabled = $enabled;

        return $this;
    }
}
