<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-06-10
 * Time: 02:04
 */

namespace App\Form\Model;

use App\Entity\User\User;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;


class ChangePassword {

    /**
     * @Assert\NotNull(message="A password atual é obrigatória.")
     * @SecurityAssert\UserPassword(message="A password actual está incorrecta.")
     */
    private $oldPassword;

    /**
     * @Assert\NotNull(message="A nova password é obrigatória.")
     * @Assert\Length(
     *     min=6,
     *     minMessage="A nova password deve ter pelo menos 6 caracteres.",
     *     max=64,
     *     maxMessage="A nova password pode no máximo ter 64 caracteres."
     * )
     */
    private $newPassword;

    public function __construct() {
    }

    public function update(User $user){
        $user->setPlainPassword($this->getNewPassword());
        $user->setPassword("");
    }

    /**
     * @return ?string
     */
    public function getOldPassword(): ?string {
        return $this->oldPassword;
    }

    /**
     * @param ?string $oldPassword
     * @return self
     */
    public function setOldPassword($oldPassword): self {
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
     * @param ?string $newPassword
     * @return self
     */
    public function setNewPassword($newPassword): self {
        $this->newPassword = $newPassword;
        return $this;
    }

}