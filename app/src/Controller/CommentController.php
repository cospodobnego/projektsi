<?php
/**
 * Comment controller.
 */
namespace App\Controller;
use App\Entity\Comment;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \Knp\Component\Pager\PaginatorInterface;
/**
 * Class CommentController.
 *
 * @Route("/comment")
 */
class CommentController extends AbstractController
{ /**
 * Index action.
 *
 * @param \Symfony\Component\HttpFoundation\Request $request        HTTP request
 * @param \App\Repository\CommentRepository            $commentRepository Comment repository
 * @param \Knp\Component\Pager\PaginatorInterface   $paginator      Paginator
 *
 * @return \Symfony\Component\HttpFoundation\Response HTTP response
 *
 * @Route(
 *     "/",
 *     name="comment_index",
 * )
 */
    public function index(Request $request, CommentRepository $commentRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $commentRepository->queryAll(),
            $request->query->getInt('page', 1),
            CommentRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render(
            'comment/index.html.twig',
            ['pagination' => $pagination]
        );
    }
    /**
     * Show action.
     *
     * @param \App\Entity\Comment $comment Comennt entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="comment_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(Comment $comment): Response
    {
        return $this->render(
            'comment/show.html.twig',
            ['comment' => $comment]
        );
    }
}
