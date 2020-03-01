<?php

namespace App\Controller;

use League\Flysystem\FileNotFoundException;
use League\Flysystem\FilesystemInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PublicStorageController
 * @package App\Controller
 * @Route("/storage")
 */
class PublicStorageController extends AbstractController {

    /**
     * @Route("/{path}", name="public_storage", requirements={"path" = ".+"})
     * @param string $path
     * @param FilesystemInterface $publicStorage
     * @return Response
     * @throws FileNotFoundException
     */
    public function path(string $path, FilesystemInterface $publicStorage): Response {
        return new BinaryFileResponse($publicStorage->readStream($path));
    }
}