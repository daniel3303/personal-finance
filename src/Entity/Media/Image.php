<?php

namespace App\Entity\Media;

use App\Entity\TaxPayer\TaxPayer;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Media\ImageRepository")
 */
class Image {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="string", length=512, nullable=true)
     */
    private ?string $filename = null;

    /**
     * @ORM\Column(type="string", length=512, nullable=true)
     */
    private ?string $context = null;

    /**
     * @ORM\Column(type="string", length=512, nullable=true)
     */
    private ?string $relativePath = null;

    /**
     * @ORM\Column(type="string", length=512, nullable=true)
     */
    private ?string $absolutePath = null;

    /**
     * @ORM\Column(type="string", length=16, nullable=true)
     */
    private ?string $extension = null;

    /**
     * @ORM\Column(type="integer")
     */
    private int $width = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private int $height = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private int $size = 0;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTime $creationTime = null;

    public function __construct() {
        $this->creationTime = new DateTime();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(?string $name): self {
        $this->name = $name;

        return $this;
    }

    public function getFilename(): ?string {
        return $this->filename;
    }

    public function setFilename(?string $filename): self {
        $this->filename = $filename;

        return $this;
    }

    public function getContext(): ?string {
        return $this->context;
    }

    public function setContext(?string $context): self {
        $this->context = $context;

        return $this;
    }

    public function getRelativePath(): ?string {
        return $this->relativePath;
    }

    public function setRelativePath(?string $relativePath): self {
        $this->relativePath = $relativePath;

        return $this;
    }

    public function getExtension(): ?string {
        return $this->extension;
    }

    public function setExtension(?string $extension): self {
        $this->extension = $extension;

        return $this;
    }

    public function getAbsolutePath(): ?string {
        return $this->absolutePath;
    }

    public function setAbsolutePath(?string $pathname): self {
        $this->absolutePath = $pathname;

        return $this;
    }

    public function getAbsolutePathname(): ?string {
        return $this->getAbsolutePath() . DIRECTORY_SEPARATOR . $this->getFilename();
    }

    public function getWidth(): ?int {
        return $this->width;
    }

    public function setWidth(int $width): self {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?int {
        return $this->height;
    }

    public function setHeight(int $height): self {
        $this->height = $height;

        return $this;
    }

    public function getSize(): ?int {
        return $this->size;
    }

    public function setSize(int $size): self {
        $this->size = $size;

        return $this;
    }

    public function getCreationTime(): DateTime {
        return $this->creationTime;
    }

    public function setCreationTime(DateTime $creationTime): self {
        $this->creationTime = $creationTime;

        return $this;
    }

    public function __toString() : string {
        return (string) $this->getAbsolutePathname();
    }

    public function __clone() {
        $this->id = null;
    }
}