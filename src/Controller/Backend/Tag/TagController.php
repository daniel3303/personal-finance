<?php

namespace App\Controller\Backend\Tag;

use App\Controller\Backend\BaseController;
use App\Dto\Tag\TagData;
use App\Entity\Tag\Tag;
use App\Form\Tag\TagType;
use App\Repository\Tag\TagRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/backend/tag")
 */
class TagController extends BaseController {
    /**
     * @Route("/", name="backend_tag_index", methods={"GET"})
     * @param TagRepository $tagRepository
     * @param Request $request
     * @return Response
     */
    public function index(TagRepository $tagRepository, Request $request): Response {
        /** @var Tag[] $tags */
        $tags = $this->paginate($tagRepository->findAllWithQuery(), $request, [
            'defaultSortFieldName' => 'o.name',
            'defaultSortDirection' => 'asc'
        ]);

        return $this->render('backend/tag/index.html.twig', [
            'tags' => $tags,
        ]);
    }

    /**
     * @Route("/new", name="backend_tag_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response {
        $tagData = new TagData();
        $form = $this->createForm(TagType::class, $tagData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tagData->createOrUpdateEntity());
            $entityManager->flush();

            return $this->redirectToRoute('backend_tag_index');
        }

        return $this->render('backend/tag/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="backend_tag_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Tag $tag
     * @return Response
     */
    public function edit(Request $request, Tag $tag): Response {
        $tagData = new TagData($tag);
        $form = $this->createForm(TagType::class, $tagData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_tag_index', [
                'id' => $tag->getId(),
            ]);
        }

        return $this->render('backend/tag/edit.html.twig', [
            'tag' => $tag,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="backend_tag_delete", methods={"DELETE"})
     * @param Request $request
     * @param Tag $tag
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function delete(Request $request, Tag $tag): Response {
        if ($this->isCsrfTokenValid('delete' . $tag->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tag);
            $entityManager->flush();
        }

        return $this->redirectToRoute('backend_tag_index');
    }
}
