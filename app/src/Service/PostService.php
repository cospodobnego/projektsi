<?php
/**
 * Post service.
 */

namespace App\Service;

use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class PostService.
 */
class PostService
{
    /**
     * Post repository.
     *
     * @var \App\Repository\PostRepository
     */
    private $postRepository;

    /**
     * Paginator.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * Category service.
     *
     * @var \App\Service\CategoryService
     */
    private $categoryService;

    /**
     * Tag service.
     *
     * @var \App\Service\TagService
     */
    private $tagService;

    /**
     * PostService constructor.
     *
     * @param \App\Repository\PostRepository          $postRepository  Post repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator       Paginator
     * @param \App\Service\CategoryService            $categoryService Category service
     * @param \App\Service\TagService                 $tagService      Tag service
     */
    public function __construct(PostRepository $postRepository, PaginatorInterface $paginator, CategoryService $categoryService, TagService $tagService)
    {
        $this->postRepository = $postRepository;
        $this->paginator = $paginator;
        $this->categoryService = $categoryService;
        $this->tagService = $tagService;
    }

    /**
     * Create paginated list.
     *
     * @param int   $page    Page number
     * @param array $filters Filters array
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface Paginated list
     */
    public function createPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->postRepository->queryAll(),
            $page,
            PostRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Show user's posts list.
     *
     * @param int              $page Page number
     * @param \App\Entity\User $user User
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface Paginated list
     */
    public function showUsersPosts(int $page, User $user): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->postRepository->queryByAuthor($user),
            $page,
            PostRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save post.
     *
     * @param \App\Entity\Post $post Post entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Post $post): void
    {
        $this->postRepository->save($post);
    }

    /**
     * Delete post.
     *
     * @param \App\Entity\Post $post Post entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Post $post): void
    {
        $this->postRepository->delete($post);
    }
}
