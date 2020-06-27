<?php
/**
 * Registration service.
 */

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

/**
 * Class RegistrationService.
 */
class RegistrationService
{
    /**
     * User repository.
     *
     * @var \App\Repository\UserRepository
     */
    private $userRepository;

    /**
     * Paginator.
     *
     * @param UserRepository $userRepository
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Save user.
     *
     * @param \App\Entity\User $user User entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(User $user): void
    {
        $this->userRepository->saveUser($user);
    }
}
