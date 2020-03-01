<?php


namespace App\Twig;


use App\Service\UploaderHelper;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

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