<?php

namespace App\Controller\Backend\Transaction;

use App\Controller\Backend\BaseController;
use App\Dto\Transaction\ExpenseData;
use App\Entity\Transaction\Expense;
use App\Form\Transaction\ExpenseType;
use App\Repository\Transaction\ExpenseRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/backend/expense")
 */
class ExpenseController extends BaseController {
    /**
     * @Route("/", name="backend_transaction_expense_index", methods={"GET"})
     * @param ExpenseRepository $expenseRepository
     * @param Request $request
     * @return Response
     */
    public function index(ExpenseRepository $expenseRepository, Request $request): Response {
        /** @var Expense[] $expenses */
        $expenses = $this->paginate($expenseRepository->findAllForUserWithQuery($this->user), $request, [
            'defaultSortFieldName' => 'o.time',
            'defaultSortDirection' => 'desc'
        ]);

        return $this->render('backend/transaction/expense/index.html.twig', [
            'expenses' => $expenses,
        ]);
    }

    /**
     * @Route("/new", name="backend_transaction_expense_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response {
        $expenseData = new ExpenseData();
        $form = $this->createForm(ExpenseType::class, $expenseData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($expenseData->createOrUpdateEntity());
            $entityManager->flush();

            return $this->redirectToRoute('backend_transaction_expense_index');
        }

        return $this->render('backend/transaction/expense/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_transaction_expense_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Expense $expense
     * @return Response
     */
    public function edit(Request $request, Expense $expense): Response {
        $expenseData = new ExpenseData($expense);
        $form = $this->createForm(ExpenseType::class, $expenseData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $expenseData->createOrUpdateEntity();
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_transaction_expense_index', [
                'id' => $expense->getId(),
            ]);
        }

        return $this->render('backend/transaction/expense/edit.html.twig', [
            'expense' => $expense,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backend_transaction_expense_delete", methods={"DELETE"})
     * @param Request $request
     * @param Expense $expense
     * @return Response
     */
    public function delete(Request $request, Expense $expense): Response {
        if ($this->isCsrfTokenValid('delete' . $expense->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($expense);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_transaction_expense_index');
    }
}
