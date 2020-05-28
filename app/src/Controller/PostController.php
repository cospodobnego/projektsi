<?php
/**
 * Post controller.
 */
namespace App\Controller;
use App\Entity\Post;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \Knp\Component\Pager\PaginatorInterface;
/**
 * Class PostController.
 *
 * @Route("/post")
 */
class PostController extends AbstractController
{ /**
 * Index action.
 *
 * @param \Symfony\Component\HttpFoundation\Request $request        HTTP request
 * @param \App\Repository\PostRepository            $postRepository Post repository
 * @param \Knp\Component\Pager\PaginatorInterface   $paginator      Paginator
 *
 * @return \Symfony\Component\HttpFoundation\Response HTTP response
 *
 * @Route(
 *     "/",
 *     name="post_index",
 * )
 */
    public function index(Request $request, PostRepository $postRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $postRepository->queryAll(),
            $request->query->getInt('page', 1),
            PostRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render(
            'post/index.html.twig',
            ['pagination' => $pagination]
        );
    }
    /**
     * Show action.
     *
     * @param \App\Entity\Post $post Post entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="post_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(Post $post): Response
    {
        return $this->render(
            'post/show.html.twig',
            ['post' => $post]
        );
    }
}
