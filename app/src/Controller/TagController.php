<?php
/**
 * Comment controller.
 */

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Service\TagService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;

/**
 * Class TagController.
 *
 * @Route("/tag")
 */
class TagController extends AbstractController
{
    /**
     * Tag Service.
     *
     * @var \App\Service\TagService
     */
        private $tagService;

        /**
         * TagController constructor.
         *
         * @param \App\Service\TagService $tagService Tag service
         */
    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="tag_index",
     * )
     *
     */
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->tagService->createPaginatedList($page);

        return $this->render(
            'tag/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param \App\Entity\Tag $tag Comennt entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="tag_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(Tag $tag): Response
    {
        return $this->render(
            'tag/show.html.twig',
            ['tag' => $tag]
        );
    }

    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="tag_create",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function create(Request $request): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagService->save($tag);
            $this->addFlash('success', 'message.created_successfully');

            return $this->redirectToRoute('tag_index');
        }

        return $this->render(
            'tag/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\Tag                           $tag     Tag entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="tag_edit",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Tag $tag): Response
    {
        $form = $this->createForm(TagType::class, $tag, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagService->save($tag);
            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('tag_index');
        }

        return $this->render(
            'tag/edit.html.twig',
            [
                'form' => $form->createView(),
                'tag' => $tag,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\Tag                           $tag     Tag entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="tag_delete",
     * )
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Tag $tag): Response
    {
        if ($tag->getPosts()->count()) {
            $this->addFlash('warning', 'message_tag_contains_posts');

            return $this->redirectToRoute('tag_index');
        }
        $form = $this->createForm(FormType::class, $tag, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->tagService->delete($tag);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('tag_index');
        }

        return $this->render(
            'tag/delete.html.twig',
            [
                'form' => $form->createView(),
                'tag' => $tag,
            ]
        );
    }
}
