<?php

namespace App\Controller\Backend;

use App\Repository\User\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend")
 */
class DashboardController extends BaseController {

    /**
     * @Route("/dashboard", name="backend_dashboard")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function index(UserRepository $userRepository) : Response {
        $netWorth = $userRepository->sumNetWorth($this->user);
        return $this->render('backend/dashboard/index.html.twig', [
            'netWorth' => $netWorth,
        ]);
    }
}
