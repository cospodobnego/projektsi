<?php
/**
 * Comment service.
 */

namespace App\Service;


use App\Entity\Comment;
use App\Entity\User;
use App\Repository\CommentRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;


/**
 * Class CommentService.
 */
class CommentService
{
    /**
     * Comment repository.
     *
     * @var \App\Repository\CommentRepository
     */
    private $commentRepository;
    /**
     * Paginator.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * CategoryService constructor.
     *
     * @param \App\Repository\CategoryRepository      $categoryRepository Category repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator          Paginator
     */
    public function __construct(CommentRepository $commentRepository, PaginatorInterface $paginator)
    {
        $this->commentRepository = $commentRepository;
        $this->paginator = $paginator;
    }

    /**
     * Create paginated list.
     *
     * @param int $page Page number
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface Paginated list
     */
    public function createPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->commentRepository->queryAll(),
            $page,
            CommentRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }
    /**
     * Create paginated list of usercomments.
     *
     * @param int $page Page number
     * @param \App\Entity\User $user User
     * @return \Knp\Component\Pager\Pagination\PaginationInterface Paginated list
     */
    public function createPaginatedList1(int $page, User $user): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->commentRepository->queryByAuthor($user),
            $page,
            CommentRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save comment.
     *
     * @param \App\Entity\Comment $comment Comment entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Comment $comment): void
    {
        $this->commentRepository->save($comment);
    }

    /**
     * Delete comment.
     *
     * @param \App\Entity\Comment $comment Comment entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Comment $comment): void
    {
        $this->commentRepository->delete($comment);
    }
}
