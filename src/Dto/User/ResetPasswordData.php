<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-06-10
 * Time: 02:04
 */

namespace App\Dto\User;

use App\Entity\User\User;
use Symfony\Component\Validator\Constraints as Assert;


class ResetPasswordData {
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

    public function __construct() {
    }

    public function update(User $user): void {
        $user->setPlainPassword($this->getNewPassword());
        $user->setPassword('');
    }

    /**
     * @return string|null
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