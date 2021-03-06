<?php

namespace App\Entity\User;


use App\Entity\Account\Account;
use App\Entity\Media\Image;
use Carbon\Carbon;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use libphonenumber\PhoneNumber;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @ORM\Entity(repositoryClass="App\Repository\User\UserRepository")
 */
class User implements UserInterface {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $enabled;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Media\Image", cascade={"persist", "remove"})
     */
    private ?Image $photo = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private string $gender;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private string $email;

    /**
     * @ORM\Column(type="phone_number", nullable=true)
     */
    private ?PhoneNumber $phone = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $birthday;


    /**
     * @ORM\Column(type="json")
     */
    private array $roles;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private ?string $password = null;

    /**
     * @var string|null Plain password
     */
    private ?string $plainPassword = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $creationTime;

    /**
     * @ORM\Column(type="string", length=512, nullable=true)
     */
    private ?string $passwordToken = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $passwordTokenExpirationTime = null;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Account\Account", mappedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private Collection $accounts;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private string $locale;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private string $timezone;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private string $currencyCode;

    public function __construct(bool $enabled, string $name, string $gender, string $email,
                                ?PhoneNumber $phone, DateTime $birthday, array $roles, string $language,
                                string $timezone, string $currencyCode) {
        $this->enabled = $enabled;
        $this->name = $name;
        $this->gender = $gender;
        $this->email = $email;
        $this->phone = $phone;
        $this->birthday = $birthday;
        $this->roles = $roles;
        $this->creationTime = new DateTime();
        $this->accounts = new ArrayCollection();
        $this->locale = $language;
        $this->timezone = $timezone;
        $this->currencyCode = $currencyCode;
    }


    public function getId(): ?int {
        return $this->id;
    }

    public function isEnabled(): bool {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self {
        $this->enabled = $enabled;

        return $this;
    }

    public function getPhoto(): ?Image {
        return $this->photo;
    }

    public function setPhoto(?Image $photo): self {
        $this->photo = $photo;

        return $this;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email): self {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string {
        return $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string {
        return (string)$this->password;
    }

    public function setPassword(string $password): self {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt() {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void {
        $this->plainPassword = null;
    }

    public function __toString() {
        return $this->email;
    }

    public function getPlainPassword(): ?string {
        return $this->plainPassword;
    }


    public function setPlainPassword(string $plainPassword): void {
        $this->setPassword('');
        $this->plainPassword = $plainPassword;
        $this->setPasswordToken(null);
        $this->setPasswordTokenExpirationTime(null);
    }

    public function getCreationTime(): Carbon {
        return Carbon::instance($this->creationTime);
    }

    public function setCreationTime(DateTime $creationTime): self {
        $this->creationTime = $creationTime;

        return $this;
    }

    public function getPasswordToken(): ?string {
        return $this->passwordToken;
    }

    public function setPasswordToken(?string $passwordToken): self {
        $this->passwordToken = $passwordToken;

        return $this;
    }

    public function getPasswordTokenExpirationTime(): ?Carbon {
        return $this->passwordTokenExpirationTime !== null ? Carbon::instance($this->passwordTokenExpirationTime) : null;
    }

    public function setPasswordTokenExpirationTime(?DateTime $passwordTokenExpirationTime): self {
        $this->passwordTokenExpirationTime = $passwordTokenExpirationTime;

        return $this;
    }

    public function getPhone(): ?PhoneNumber {
        return $this->phone;
    }

    public function setPhone(?PhoneNumber $phone): self {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getGender(): string {
        return $this->gender;
    }

    /**
     * @param string
     * @return User
     */
    public function setGender(string $gender): self {
        $this->gender = $gender;
        return $this;
    }

    public function getBirthday(): Carbon {
        return Carbon::instance($this->birthday);
    }

    public function setBirthday(DateTime $birthday): self {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * @return Collection|Account[]
     */
    public function getAccounts(): Collection {
        return $this->accounts;
    }

    public function addAccount(Account $account): self {
        if (!$this->accounts->contains($account)) {
            $this->accounts[] = $account;
            $account->setUser($this);
        }

        return $this;
    }

    public function removeAccount(Account $account): self {
        if ($this->accounts->contains($account)) {
            $this->accounts->removeElement($account);
        }

        return $this;
    }

    public function getLocale(): string {
        return $this->locale;
    }

    public function setLocale(string $locale): self {
        $this->locale = $locale;

        return $this;
    }

    public function getTimezone(): string {
        return $this->timezone;
    }

    public function setTimezone(string $timezone): self {
        $this->timezone = $timezone;

        return $this;
    }

    public function getCurrencyCode(): string {
        return $this->currencyCode;
    }

    public function setCurrencyCode(string $currencyCode): self {
        $this->currencyCode = $currencyCode;

        return $this;
    }
}
