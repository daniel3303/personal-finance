<?php

namespace App\Controller\Backend\Transaction;

use App\Controller\Backend\BaseController;
use App\Dto\Transaction\RecurrentTransactionData;
use App\Entity\Transaction\RecurrentTransaction;
use App\Form\Transaction\RecurrentTransactionType;
use App\Repository\Transaction\RecurrentTransactionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/backend/recurrent-trasactions")
 */
class RecurrentTransactionController extends BaseController {
    /**
     * @Route("/", name="backend_transaction_recurrent_index", methods={"GET"})
     * @param RecurrentTransactionRepository $recurrentTransactionRepository
     * @param Request $request
     * @return Response
     */
    public function index(RecurrentTransactionRepository $recurrentTransactionRepository, Request $request): Response {
        /** @var RecurrentTransaction[] $recurrentTransactions */
        $recurrentTransactions = $this->paginate($recurrentTransactionRepository->findAllForUserWithQuery($this->user), $request, [
            'defaultSortFieldName' => 'o.creationTime',
            'defaultSortDirection' => 'desc'
        ]);

        return $this->render('backend/transaction/recurrent/index.html.twig', [
            'recurrentTransactions' => $recurrentTransactions,
        ]);
    }

    /**
     * @Route("/new", name="backend_transaction_recurrent_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response {
        $recurrentTransactionData = new RecurrentTransactionData();
        $form = $this->createForm(RecurrentTransactionType::class, $recurrentTransactionData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($recurrentTransactionData->createOrUpdateEntity());
            $entityManager->flush();

            return $this->redirectToRoute('backend_transaction_recurrent_index');
        }

        return $this->render('backend/transaction/recurrent/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_transaction_recurrent_edit", methods={"GET","POST"})
     * @param Request $request
     * @param RecurrentTransaction $recurrentTransaction
     * @return Response
     */
    public function edit(Request $request, RecurrentTransaction $recurrentTransaction): Response {
        $recurrentTransactionData = new RecurrentTransactionData($recurrentTransaction);
        $form = $this->createForm(RecurrentTransactionType::class, $recurrentTransactionData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recurrentTransactionData->createOrUpdateEntity();
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_transaction_recurrent_index', [
                'id' => $recurrentTransaction->getId(),
            ]);
        }

        return $this->render('backend/transaction/recurrent/edit.html.twig', [
            'recurrentTransaction' => $recurrentTransaction,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backend_transaction_recurrent_delete", methods={"DELETE"})
     * @param Request $request
     * @param RecurrentTransaction $recurrentTransaction
     * @return Response
     */
    public function delete(Request $request, RecurrentTransaction $recurrentTransaction): Response {
        if ($this->isCsrfTokenValid('delete' . $recurrentTransaction->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($recurrentTransaction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_transaction_recurrent_index');
    }
}
