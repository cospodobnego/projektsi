<?php
/**
 * Comment controller.
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Service\CommentService;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;

/**
 * Class CommentController.
 *
 * @Route("/comment")
 */
class CommentController extends AbstractController
{
    /**
     * Comment Service.
     *
     * @var \App\Service\CommentService
     */
    private $commentService;

    /**
     * CommentsController constructor.
     *
     * @param \App\Service\CommentService $commentService Category service
     */
    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request           HTTP request
     * @param \App\Repository\CommentRepository         $commentRepository Comment repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator         Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     name="comment_index",
     * )
     */
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->commentService->createPaginatedList($page);


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

    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request           HTTP request
     * @param \App\Entity\Post                          $post    Post entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/create",
     *     methods={"GET", "POST"},
     *     name="comment_create",
     * )
     * @IsGranted(
     *     "ROLE_USER",
     *
     * )
     */
    public function create(Request $request, Post $post): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setAuthor($this->getUser());
            $comment->setPost($post);
            $this->commentService->save($comment);
            $this->addFlash('success', 'created_successfully');

            return $this->redirectToRoute('post_show',['id' => $post->getId()]);
        }

        return $this->render(
            'comment/create.html.twig',
            [   'post' => $post,
                'form' => $form->createView()
            ]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request           HTTP request
     * @param \App\Entity\Comment                       $comment           Comment entity
     * @param \App\Repository\CommentRepository         $commentRepository Comment repository
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
     *     name="comment_edit",
     * )
     * @IsGranted(
     *     "EDIT",
     *     subject="comment",
     * )
     */
    public function edit(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(CommentType::class, $comment, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())

        {
            $this->commentService->save($comment);
            $this->addFlash('success', 'updated_successfully');

            return $this->redirectToRoute('post_show',['id' => $comment->getPost()->getId()]);
        }

        return $this->render(
            'comment/edit.html.twig',
            [
                'form' => $form->createView(),
                'comment' => $comment,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request           HTTP request
     * @param \App\Entity\Comment                       $comment           Comment entity
     * @param \App\Repository\CommentRepository         $commentRepository Comment repository
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
     *     name="comment_delete",
     * )
     * @IsGranted(
     *     "DELETE",
     *     subject="comment",
     * )
     */
    public function delete(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(FormType::class, $comment, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $postId = $comment->getPost()->getId();
            $this->commentService->delete($comment);
            $this->addFlash('success', 'deleted successfully');

            return $this->redirectToRoute('post_show',['id' => $postId]);
        }

        return $this->render(
            'comment/delete.html.twig',
            [
                'form' => $form->createView(),
                'comment' => $comment,
            ]
        );
    }

    /**
     * My comments action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request           HTTP request
     *
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator         Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/comments",
     *     methods={"GET"},
     *     name="comment_mycomments",
     * )
     */
    public function myComments(Request $request): Response
    {
        $user = $this->getUser();
        $page = $request->query->getInt('page', 1);
        $pagination = $this->commentService->createPaginatedList1($page, $user);

        return $this->render(
            'comment/mycomments.html.twig',
            ['pagination' => $pagination]
        );
    }
}
