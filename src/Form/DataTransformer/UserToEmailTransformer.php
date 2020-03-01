<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 01/03/2020
 * Time: 00:00
 */

namespace App\Form\DataTransformer;
use App\Entity\User\User;
use App\Repository\User\UserRepository;
use Symfony\Component\Form\DataTransformerInterface;

class UserToEmailTransformer implements DataTransformerInterface {
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function transform($value) {
        if($value instanceof User){
            return $value->getUsername();
        }
        return $value;
    }
    public function reverseTransform($value) {
        return $this->userRepository->findOneBy(['email' => $value]);
    }
}