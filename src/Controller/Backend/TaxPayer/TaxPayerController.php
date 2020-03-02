<?php

namespace App\Controller\Backend\TaxPayer;

use App\Controller\Backend\BaseController;
use App\Dto\TaxPayer\CategoryData;
use App\Entity\TaxPayer\TaxPayer;
use App\Form\TaxPayer\TaxPayerType;
use App\Repository\TaxPayer\TaxPayerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/backend/tax-payers")
 */
class TaxPayerController extends BaseController {
    /**
     * @Route("/", name="backend_tax_payer_index", methods={"GET"})
     * @param TaxPayerRepository $taxPayerRepository
     * @param Request $request
     * @return Response
     */
    public function index(TaxPayerRepository $taxPayerRepository, Request $request): Response {
        /** @var TaxPayer[] $taxPayers */
        $taxPayers = $this->paginate($taxPayerRepository->findAllWithQuery(), $request, [
            'defaultSortFieldName' => 'o.name',
            'defaultSortDirection' => 'asc'
        ]);

        return $this->render('backend/tax_payer/index.html.twig', [
            'taxPayers' => $taxPayers,
        ]);
    }

    /**
     * @Route("/new", name="backend_tax_payer_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response {
        $taxPayerData = new CategoryData();
        $form = $this->createForm(TaxPayerType::class, $taxPayerData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($taxPayerData->createOrUpdateEntity());
            $entityManager->flush();

            return $this->redirectToRoute('backend_tax_payer_index');
        }

        return $this->render('backend/tax_payer/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_tax_payer_edit", methods={"GET","POST"})
     * @param Request $request
     * @param TaxPayer $taxPayer
     * @return Response
     */
    public function edit(Request $request, TaxPayer $taxPayer): Response {
        $taxPayerData = new CategoryData($taxPayer);
        $form = $this->createForm(TaxPayerType::class, $taxPayerData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_user_index', [
                'id' => $taxPayer->getId(),
            ]);
        }

        return $this->render('backend/tax_payer/edit.html.twig', [
            'taxPayer' => $taxPayer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backend_tax_payer_delete", methods={"DELETE"})
     * @param Request $request
     * @param TaxPayer $taxPayer
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function delete(Request $request, TaxPayer $taxPayer, TranslatorInterface $translator): Response {
        if ($this->isCsrfTokenValid('delete' . $taxPayer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($taxPayer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_tax_payer_index');
    }
}
