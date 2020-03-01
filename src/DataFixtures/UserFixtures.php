<?php

namespace App\DataFixtures;

use App\Entity\Media\Image;
use App\Entity\User\User;
use App\Form\Type\GenderType;
use Carbon\Carbon;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends BaseFixture {
    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function loadData(ObjectManager $manager) {
        $this->createMany(User::class, 1, function (int $count) {
            /**
             * @var Image $image
             */
            $image = clone $this->getRandomReference(Image::class);
            $user = new User(true,'Admin', GenderType::MALE, 'admin@domain.tld', null,
                Carbon::create(1997, 02, 13), ['ROLE_ADMIN']);

            $user->setPlainPassword('123456');
            return $user;

        });
    }
}
