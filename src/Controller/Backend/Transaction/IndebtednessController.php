<?php

namespace App\Controller\Backend\Transaction;

use App\Controller\Backend\BaseController;
use App\Dto\Transaction\IndebtednessData;
use App\Entity\Transaction\Indebtedness;
use App\Form\Transaction\IndebtednessType;
use App\Repository\Transaction\IndebtednessRepository;
use Hoa\Stream\Test\Unit\IStream\In;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/backend/indebtednessess")
 */
class IndebtednessController extends BaseController {
    /**
     * @Route("/", name="backend_transaction_indebtedness_index", methods={"GET"})
     * @param IndebtednessRepository $indebtednessRepository
     * @param Request $request
     * @return Response
     */
    public function index(IndebtednessRepository $indebtednessRepository, Request $request): Response {
        /** @var Indebtedness[] $indebtednesses */
        $indebtednesses = $this->paginate($indebtednessRepository->findAllWithQuery(), $request, [
            'defaultSortFieldName' => 'o.time',
            'defaultSortDirection' => 'desc'
        ]);

        return $this->render('backend/transaction/indebtedness/index.html.twig', [
            'indebtednesses' => $indebtednesses,
        ]);
    }

    /**
     * @Route("/new", name="backend_transaction_indebtedness_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response {
        $indebtednessData = new IndebtednessData();
        $form = $this->createForm(IndebtednessType::class, $indebtednessData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($indebtednessData->createOrUpdateEntity());
            $entityManager->flush();

            return $this->redirectToRoute('backend_transaction_indebtedness_index');
        }

        return $this->render('backend/transaction/indebtedness/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_transaction_indebtedness_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Indebtedness $indebtedness
     * @return Response
     */
    public function edit(Request $request, Indebtedness $indebtedness): Response {
        $indebtednessData = new IndebtednessData($indebtedness);
        $form = $this->createForm(IndebtednessType::class, $indebtedness);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_user_index', [
                'id' => $indebtedness->getId(),
            ]);
        }

        return $this->render('backend/transaction/indebtedness/edit.html.twig', [
            'indebtedness' => $indebtedness,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backend_transaction_indebtedness_delete", methods={"DELETE"})
     * @param Request $request
     * @param Indebtedness $indebtedness
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function delete(Request $request, Indebtedness $indebtedness, TranslatorInterface $translator): Response {
        if ($this->isCsrfTokenValid('delete' . $indebtedness->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($indebtedness);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_transaction_indebtedness_index');
    }
}
