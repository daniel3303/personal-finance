<?php

namespace App\Controller\Backend;

use App\Entity\User\User;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend")
 */
class DashboardController extends BaseController {

    /**
     * @Route("/dashboard", name="backend_dashboard")
     */
    public function index(Request $request) {


        return $this->render('backend/dashboard/index.html.twig', [
        ]);
    }
}