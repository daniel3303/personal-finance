<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class WelcomeController extends AbstractController {
    /**
     * @Route("/", name="welcome")
     */
    public function index(): RedirectResponse {
        return $this->redirectToRoute('backend_dashboard');
    }

    /**
     * @Route("/backend", name="welcome_backend")
     */
    public function backendIndex() : RedirectResponse {
        return $this->redirectToRoute('backend_dashboard');
    }


}
