<?php


namespace App\Twig;


use App\Entity\Media\Image;
use App\Service\UploaderHelper;
use League\Flysystem\FilesystemInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Asset\Context\RequestStackContext;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class PublicStorageExtension extends AbstractExtension {


    /**
     * @var RouterInterface
     */
    private RouterInterface $router;

    public function __construct(RouterInterface $router) {
        $this->router = $router;
    }

    public function getFilters(): array {
        return [
            new TwigFilter('public_storage', [$this, 'getPublicStorageFile'])
        ];
    }


    public function getPublicStorageFile(?string $path): ?string {
        if ($path === null) {
            return '';
        }

        return $this->router->generate('public_storage', ['path' => $path]);
    }
}