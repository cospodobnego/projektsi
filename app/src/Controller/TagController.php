<?php
/**
 * Comment controller.
 */
namespace App\Controller;
use App\Entity\Tag;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \Knp\Component\Pager\PaginatorInterface;
/**
 * Class TagController.
 *
 * @Route("/tag")
 */
class TagController extends AbstractController
{ /**
 * Index action.
 *
 * @param \Symfony\Component\HttpFoundation\Request $request        HTTP request
 * @param \App\Repository\TagRepository            $tagRepository Tag repository
 * @param \Knp\Component\Pager\PaginatorInterface   $paginator      Paginator
 *
 * @return \Symfony\Component\HttpFoundation\Response HTTP response
 *
 * @Route(
 *     "/",
 *     name="tag_index",
 * )
 */
    public function index(Request $request, TagRepository $tagRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $tagRepository->queryAll(),
            $request->query->getInt('page', 1),
            TagRepository::PAGINATOR_ITEMS_PER_PAGE
        );

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
}
