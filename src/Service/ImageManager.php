<?php


namespace App\Service;


use App\Entity\Media\Image;
use League\Flysystem\FileExistsException;
use League\Flysystem\FilesystemInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageManager {
    /**
     * @var string
     */
    private string $imagesPath;

    /**
     * @var FilesystemInterface
     */
    private FilesystemInterface $publicStorage;

    public function __construct(string $imagesPath, FilesystemInterface $publicStorage) {
        $this->imagesPath = $imagesPath;
        $this->publicStorage = $publicStorage;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param string $context
     * @param Image|null $image
     * @return Image|null
     * @throws FileExistsException
     * @throws \Exception
     * @throws \Exception
     */
    public function upload(UploadedFile $uploadedFile, string $context, ?Image $image = null): ?Image {
        if ($uploadedFile->isValid() === false) {
            return null;
        }

        if(!$image) {
            $image = new Image();
        }
        $image->setName($uploadedFile->getClientOriginalName());
        $image->setContext($context);
        $image->setSize($uploadedFile->getSize());
        [$width, $height] = getimagesize($uploadedFile->getPathname());
        $image->setWidth($width);
        $image->setHeight($height);

        $destinationPath = $this->imagesPath . DIRECTORY_SEPARATOR . $context;
        $filename = Uuid::uuid4()->toString() . '.' . $uploadedFile->guessExtension();
        $image->setRelativePath($context);
        $image->setExtension($uploadedFile->guessExtension());
        $image->setFilename($filename);
        $image->setAbsolutePath($this->imagesPath . DIRECTORY_SEPARATOR . $context );

        $this->publicStorage->writeStream($destinationPath . DIRECTORY_SEPARATOR . $filename, fopen($uploadedFile->getRealPath(), 'rb'));

        return $image;
    }

}