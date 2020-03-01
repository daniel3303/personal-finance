<?php

namespace App\Controller\Backend\Transaction;

use App\Controller\Backend\BaseController;
use App\Entity\Transaction\Revenue;
use App\Form\Transaction\RevenueType;
use App\Repository\Transaction\RevenueRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/backend/revenue")
 */
class RevenueController extends BaseController {
    /**
     * @Route("/", name="backend_transaction_revenue_index", methods={"GET"})
     * @param RevenueRepository $revenueRepository
     * @param Request $request
     * @return Response
     */
    public function index(RevenueRepository $revenueRepository, Request $request): Response {
        /** @var Revenue[] $revenues */
        $revenues = $this->paginate($revenueRepository->findAllWithQuery(), $request, [
            'defaultSortFieldName' => 'o.time',
            'defaultSortDirection' => 'desc'
        ]);

        return $this->render('backend/transaction/revenue/index.html.twig', [
            'revenues' => $revenues,
        ]);
    }

    /**
     * @Route("/new", name="backend_transaction_revenue_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response {
        $revenue = new Revenue();
        $form = $this->createForm(RevenueType::class, $revenue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($revenue);
            $entityManager->flush();

            return $this->redirectToRoute('backend_transaction_revenue_index');
        }

        return $this->render('backend/transaction/revenue/new.html.twig', [
            'revenue' => $revenue,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_transaction_revenue_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Revenue $revenue): Response {
        $form = $this->createForm(RevenueType::class, $revenue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_user_index', [
                'id' => $revenue->getId(),
            ]);
        }

        return $this->render('backend/transaction/revenue/edit.html.twig', [
            'revenue' => $revenue,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backend_transaction_revenue_delete", methods={"DELETE"})
     * @param Request $request
     * @param Revenue $revenue
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function delete(Request $request, Revenue $revenue, TranslatorInterface $translator): Response {
        if ($this->isCsrfTokenValid('delete' . $revenue->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($revenue);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_transaction_revenue_index');
    }
}
