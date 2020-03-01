<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-06-10
 * Time: 02:04
 */

namespace App\Dto\User;

use App\Entity\User\User;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;


class ChangePasswordData {
    private User $entity;

    /**
     * @Assert\NotNull(message="A password atual é obrigatória.")
     * @SecurityAssert\UserPassword(message="A password actual está incorrecta.")
     */
    private ?string $oldPassword = null;

    /**
     * @Assert\NotNull(message="A nova password é obrigatória.")
     * @Assert\Length(
     *     min=6,
     *     minMessage="A nova password deve ter pelo menos 6 caracteres.",
     *     max=64,
     *     maxMessage="A nova password pode no máximo ter 64 caracteres."
     * )
     */
    private ?string $newPassword = null;

    public function __construct(User $user) {
        $this->entity = $user;
    }

    public function updateEntity(): User{
        $this->entity->setPlainPassword($this->getNewPassword());
        $this->entity->setPassword('');
        return $this->entity;
    }

    /**
     * @return string|null ?string
     */
    public function getOldPassword(): ?string {
        return $this->oldPassword;
    }

    /**
     * @param string|null $oldPassword
     * @return self
     */
    public function setOldPassword(?string $oldPassword): self {
        $this->oldPassword = $oldPassword;
        return $this;
    }

    /**
     * @return ?string
     */
    public function getNewPassword(): ?string {
        return $this->newPassword;
    }

    /**
     * @param string|null $newPassword
     * @return self
     */
    public function setNewPassword(?string $newPassword): self {
        $this->newPassword = $newPassword;
        return $this;
    }

}