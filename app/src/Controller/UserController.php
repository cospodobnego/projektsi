<?php
/**
 * User controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\UserEmailType;
use App\Form\UserPasswordType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Knp\Component\Pager\PaginatorInterface;
/**
 * Class UserController.
 *
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * Index action.
     *
     * @Route("/user_index", name="user_index")
     *
     * @IsGranted("ROLE_USER")
     */
    public function index(): Response
    {
        $userEmail = $this->getUser();

        return $this->render('user/index.html.twig', [
            'user' => $userEmail,
        ]);
    }
    /**
     * Userlist action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/user_admin_panel",
     *     methods={"GET"},
     *     name="user_admin_panel",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function Userlist(Request $request, UserRepository $userrepository,PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $userrepository->queryAll(),
            $request->query->getInt('page', 1),
            UserRepository::PAGINATOR_ITEMS_PER_PAGE
        );


        return $this->render(
            'user/admin_panel.html.twig',
            ['pagination' => $pagination]
        );
    }


    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request         HTTP request
     * @param \App\Entity\User                          $user            User entity
     * @param UserPasswordEncoderInterface              $passwordEncoder Password Encoder
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
     *     name="user_change",
     * )
     *
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserPasswordType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $userRepository->saveUser($user);
            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('post_index');
        }

        return $this->render(
            'user/change.html.twig',
            ['form' => $form->createView(),
                'user' => $this->getUser(), ]
        );
    }

    /**
     * Edit email.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Entity\User                          $user    User entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/changemail",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="user_changemail",
     * )
     *
     * @IsGranted("ROLE_USER")
     */
    public function editemail(Request $request, User $user, UserRepository $userRepository)
    {
        $form = $this->createForm(UserEmailType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->saveUser($user);
            $this->addFlash('success', 'message.changed_successfully');

            return $this->redirectToRoute('post_index');
        }

        return $this->render(
            'user/changemail.html.twig',
            ['form' => $form->createView(),
                'user' => $user, ]
        );

    }
}
