<?php

namespace App\Controller\Backend\Account;

use App\Controller\Backend\BaseController;
use App\Dto\Account\TransferData;
use App\Entity\Account\Transfer;
use App\Form\Account\TransferType;
use App\Repository\Account\TransferRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/backend/account/transfer")
 */
class TransferController extends BaseController {
    /**
     * @Route("/", name="backend_account_transfer_index", methods={"GET"})
     * @param TransferRepository $transferRepository
     * @param Request $request
     * @return Response
     */
    public function index(TransferRepository $transferRepository, Request $request): Response {
        /** @var Transfer[] $transfers */
        $transfers = $this->paginate($transferRepository->findAllWithQuery(), $request, [
            'defaultSortFieldName' => 'o.creationTime',
            'defaultSortDirection' => 'desc'
        ]);

        return $this->render('backend/account/transfer/index.html.twig', [
            'transfers' => $transfers,
        ]);
    }

    /**
     * @Route("/new", name="backend_account_transfer_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response {
        $transferData = new TransferData();
        $form = $this->createForm(TransferType::class, $transferData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($transferData->createOrUpdateEntity());
            $entityManager->flush();

            return $this->redirectToRoute('backend_account_transfer_index');
        }

        return $this->render('backend/account/transfer/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_account_transfer_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Transfer $transfer
     * @return Response
     */
    public function edit(Request $request, Transfer $transfer): Response {
        $transferData = new TransferData($transfer);
        $form = $this->createForm(TransferType::class, $transferData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_account_transfer_index', [
                'id' => $transfer->getId(),
            ]);
        }

        return $this->render('backend/account/transfer/edit.html.twig', [
            'transfer' => $transfer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backend_account_transfer_delete", methods={"DELETE"})
     * @param Request $request
     * @param Transfer $transfer
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function delete(Request $request, Transfer $transfer): Response {
        if ($this->isCsrfTokenValid('delete' . $transfer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($transfer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_account_transfer_index');
    }
}
