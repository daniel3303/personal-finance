<?php

namespace App\Controller\Backend\Account;

use App\Controller\Backend\BaseController;
use App\Dto\Account\AssetAccountData;
use App\Entity\Account\AssetAccount;
use App\Form\Account\AssetAccountType;
use App\Repository\Account\AssetAccountRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/backend/asset-accounts")
 */
class AssetAccountController extends BaseController {
    /**
     * @Route("/", name="backend_account_asset_index", methods={"GET"})
     * @param AssetAccountRepository $assetAccountRepository
     * @param Request $request
     * @return Response
     */
    public function index(AssetAccountRepository $assetAccountRepository, Request $request): Response {
        /** @var AssetAccount[] $assetAccounts */
        $assetAccounts = $this->paginate($assetAccountRepository->findAllForUserWithQuery($this->user), $request, [
            'defaultSortFieldName' => 'o.creationTime',
            'defaultSortDirection' => 'desc'
        ]);

        return $this->render('backend/account/asset/index.html.twig', [
            'assetAccounts' => $assetAccounts,
        ]);
    }

    /**
     * @Route("/new", name="backend_account_asset_new", methods={"GET","POST"})
     * @param Request $request
     * @param AssetAccountData $assetAccountData
     * @return Response
     */
    public function new(Request $request, AssetAccountData $assetAccountData): Response {
        $form = $this->createForm(AssetAccountType::class, $assetAccountData);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($assetAccountData->createOrUpdateEntity());
            $entityManager->flush();

            return $this->redirectToRoute('backend_account_asset_index');
        }

        return $this->render('backend/account/asset/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_account_asset_edit", methods={"GET","POST"})
     * @param Request $request
     * @param AssetAccount $assetAccount
     * @return Response
     */
    public function edit(Request $request, AssetAccount $assetAccount): Response {
        $assetAccountData = new AssetAccountData($assetAccount);
        $form = $this->createForm(AssetAccountType::class, $assetAccountData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_account_asset_index', [
                'id' => $assetAccount->getId(),
            ]);
        }

        return $this->render('backend/account/asset/edit.html.twig', [
            'assetAccount' => $assetAccount,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backend_account_asset_delete", methods={"DELETE"})
     * @param Request $request
     * @param AssetAccount $assetAccount
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function delete(Request $request, AssetAccount $assetAccount): Response {
        if ($this->isCsrfTokenValid('delete' . $assetAccount->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($assetAccount);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_account_asset_index');
    }
}
