<?php

namespace App\DataFixtures;

use App\Entity\Media\Image;
use App\Entity\User\User;
use App\Form\Type\GenderType;
use Carbon\Carbon;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends BaseFixture{
    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function loadData(ObjectManager $manager){
        $this->createMany(User::class, 1, function (User $user, int $count){
            /**
             * @var Image $image
             */
            $image = clone $this->getRandomReference(Image::class);

            $user->setEnabled(true);
            $user->setPhoto($image);
            $user->setEmail('admin@domain.tld');
            $user->setName('Admin');
            $user->setGender(GenderType::MALE);
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPlainPassword('123456');
            $user->setBirthday(Carbon::create(1997,02,13));
        });
    }
}
