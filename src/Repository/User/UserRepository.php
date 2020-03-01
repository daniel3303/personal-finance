<?php

namespace App\Repository\User;

use App\Entity\User\User;
use App\Repository\BaseRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends BaseRepository {
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $passwordEncoder;


    /**
     * UserRepository constructor.
     * @param ManagerRegistry $registry
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(ManagerRegistry $registry, UserPasswordEncoderInterface $encoder) {
        parent::__construct($registry, User::class);
        $this->passwordEncoder = $encoder;
    }

    /**
     * @param $username string|null
     * @param $password string|null
     * @return User Returns a single User or null
     */
    public function findOneByUsernameAndPassword(?string $username, ?string $password): ?User{
        $user = $this->findOneBy(array('email' => $username));

        //Username not found
        if($user === null){ return null; }

        if($this->passwordEncoder->isPasswordValid($user, $password) === true){
            return $user;
        }

        return null;
    }
}
