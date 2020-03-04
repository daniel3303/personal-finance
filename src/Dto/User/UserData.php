<?php

namespace App\Dto\User;


use App\Entity\Media\Image;
use App\Entity\User\User;
use DateTime;
use libphonenumber\PhoneNumber;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as PhoneNumberConstraint;
use Symfony\Component\Validator\Constraints as Assert;

class UserData {
    private ?User $entity = null;

    /**
     * @Assert\NotNull()
     */
    private ?bool $enabled = null;

    /**
     * @var Image|null
     */
    private ?Image $photo = null;

    /**
     * @Assert\NotNull(message="The name is required.")
     * @Assert\Length(max=255, maxMessage="The name can have at most {{ limit }} chars.")
     */
    private ?string $name = null;

    /**
     * @Assert\NotNull(message="The gender is required.")
     * @Assert\Choice(choices={"M", "F"})
     */
    private ?string $gender = null;

    /**
     * @Assert\NotNull()
     * @Assert\Email(message="The email address {{ value }} is not valid.")
     */
    private ?string $email = null;

    /**
     * @PhoneNumberConstraint(message="This phone number is not valid.", type="mobile")
     */
    private ?PhoneNumber $phone = null;

    /**
     * @Assert\NotNull()
     */
    private ?DateTime $birthday = null;


    /**
     * @Assert\NotNull()
     * @Assert\Type(type="array")
     */
    private ?array $roles = null;

    /**
     * @Assert\Length(
     *     min=6,
     *     minMessage="Your password must have at least {{ limit }} chars.",
     *     max=64,
     *     maxMessage="Your password must have at most {{ limit }} chars."
     * )
     * @Assert\NotCompromisedPassword(
     *     threshold=6,
     *     message="This password has been leaked in a data breach, it must not be used. Please use another password.",
     *     skipOnError=true
     * )
     */
    private ?string $plainPassword = null;

    public function __construct(?User $entity = null) {
        $this->entity = $entity;
        if($entity){
            $this->reverseTransfer($entity);
        }
    }


    /**
     * @return bool|null
     */
    public function getEnabled(): ?bool {
        return $this->enabled;
    }

    /**
     * @param bool|null $enabled
     */
    public function setEnabled(?bool $enabled): void {
        $this->enabled = $enabled;
    }

    /**
     * @return Image|null
     */
    public function getPhoto(): ?Image {
        return $this->photo;
    }

    /**
     * @param Image|null $photo
     */
    public function setPhoto(?Image $photo): void {
        $this->photo = $photo;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getGender(): ?string {
        return $this->gender;
    }

    /**
     * @param string|null $gender
     */
    public function setGender(?string $gender): void {
        $this->gender = $gender;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void {
        $this->email = $email;
    }

    /**
     * @return PhoneNumber|null
     */
    public function getPhone(): ?PhoneNumber {
        return $this->phone;
    }

    /**
     * @param PhoneNumber|null $phone
     */
    public function setPhone(?PhoneNumber $phone): void {
        $this->phone = $phone;
    }

    /**
     * @return DateTime|null
     */
    public function getBirthday(): ?DateTime {
        return $this->birthday;
    }

    /**
     * @param DateTime|null $birthday
     */
    public function setBirthday(?DateTime $birthday): void {
        $this->birthday = $birthday;
    }

    /**
     * @return array|null
     */
    public function getRoles(): ?array {
        return $this->roles;
    }

    /**
     * @param array|null $roles
     */
    public function setRoles(?array $roles): void {
        $this->roles = $roles;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string {
        return $this->plainPassword;
    }

    /**
     * @param string|null $plainPassword
     */
    public function setPlainPassword(?string $plainPassword): void {
        $this->plainPassword = $plainPassword;
    }


    public function createOrUpdateEntity() : User {
        if($this->entity !== null){
            $this->entity = new User($this->enabled, $this->name, $this->gender, $this->email, $this->phone, $this->birthday, $this->roles);
        }
        $this->transfer($this->entity);

        return $this->entity;
    }

    public function getEntity() : ?User{
        return $this->entity;
    }

    public function transfer(User $user): void {
        $user->setEnabled($this->enabled);
        $user->setName($this->name);
        $user->setGender($this->gender);
        $user->setEmail($this->email);
        $user->setPhone($this->phone);
        $user->setBirthday($this->birthday);
        $user->setPlainPassword($this->plainPassword);
        $user->setRoles($this->roles);
        $user->setPhoto($this->photo);
    }

    public function reverseTransfer(User $user): void {
        $this->enabled = $user->isEnabled();
        $this->photo = $user->getPhoto();
        $this->name = $user->getName();
        $this->gender = $user->getGender();
        $this->email = $user->getEmail();
        $this->phone = $user->getPhone();
        $this->birthday = $user->getBirthday();
        $this->roles = $user->getRoles();
        $this->plainPassword = $user->getPlainPassword();
    }
}
