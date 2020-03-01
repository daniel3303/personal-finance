<?php

namespace App\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend")
 */
class DashboardController extends BaseController {

    /**
     * @Route("/dashboard", name="backend_dashboard")
     * @param Request $request
     * @return Response
     */
    public function index() : Response {

        return $this->render('backend/dashboard/index.html.twig', []);
    }
}
