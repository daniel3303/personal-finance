<?php

namespace App\DataFixtures;

use App\Entity\Media\Image;
use App\Service\ImageManager;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageFixtures extends BaseFixture {

    /**
     * @var ImageManager
     */
    private ImageManager $imageManager;

    /**
     * @var ContainerInterface
     */
    private ContainerInterface $container;

    public function __construct(ImageManager $imageManager, ContainerInterface $container) {
        parent::__construct();
        $this->imageManager = $imageManager;
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function loadData(ObjectManager $manager) {
        $this->createMany(Image::class, 1, function (int $count) {
            $image = new Image();
            $imagePathname = $this->container->getParameter('kernel.project_dir').'/assets/images/backend/avatar.png';
            $imageFile = new UploadedFile($imagePathname, 'avatar.png', 'image/png', 0, true);
            $this->imageManager->upload($imageFile, 'user_photo', $image);
            return $image;
        });
    }

}