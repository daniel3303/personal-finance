<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-06-10
 * Time: 02:04
 */

namespace App\Dto\User;

use App\Entity\User;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;


class RecoverPasswordData {

    /**
     * @Assert\NotNull(message="O email é obrigatório.")
     * @Assert\Email(message="O formato do email não é valido.")
     */
    private ?string $email = null;

    public function __construct() {
    }

    /**
     * @return ?string
     */
    public function getEmail(): ?string {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return self
     */
    public function setEmail(?string $email): self {
        $this->email = $email;
        return $this;
    }

}