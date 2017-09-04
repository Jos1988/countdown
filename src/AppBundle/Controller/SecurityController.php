<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SecurityController
 * @package AppBundle\Controller
 */
class SecurityController extends Controller
{
    /**
     * @Route("/user/management", name="user_management")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function userManagement(Request $request)
    {
        $users = $this->get('fos_user.user_manager')->findUsers();

        return new Response(
            $this->renderView(
                '@App/Security/userManagement.html.twig',
                [
                    'users' => $users,
                    'roles' => User::$roletypes,
                    'menuStatus' => $request->query->get('menu-status'),
                ]
            )
        );
    }

    /**
     * Edit or create user.
     *
     * @param Request $request
     * @param User    $user
     *
     * @return Response
     * @Route("/user/set/{user}", name="set_user")  //todo: move actions to admin area.
     *
     */
    public function setUser(Request $request, User $user = null)
    {
        $userManager = $this->get('fos_user.user_manager');
        $title = 'Edit User.';
        if (null === $user) {
            $user = $userManager->createUser();
            $user->setRoles(['ROLE_USER']);
            $title = 'Create User.';
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $plainPassword = $user->getPassword();
            $user->setPlainPassword($plainPassword);
            $userManager->updateUser($user);

            return new Response(
                $this->redirectToRoute('user_management')
            );
        }

        return new Response(
            $this->renderView(
                '@App/Security/FormView/userForm.html.twig',
                [
                    'form' => $form->createView(),
                    'title' => $title,
                    'roles' => User::$roletypes,
                ]
            )
        );
    }

    /**
     * Switch active state.
     *
     * @param User $user
     * @route("/user/active/switch/{user}", name="user_switch_active")
     *
     * @return Response
     */
    public function switchActive(User $user)
    {
        $user->setEnabled($user->isEnabled() ? false : true);
        $this->get('fos_user.user_manager')->updateUser($user);

        return new Response(
            $this->redirectToRoute('user_management')
        );
    }

    /**
     * Delete user.
     *
     * @param User $user
     * @Route("/user/delete/{user}", name="delete_user")
     *
     * @return Response
     */
    public function deleteUser(User $user)
    {
        $this->get('fos_user.user_manager')->deleteUser($user);

        return new Response(
            $this->redirectToRoute('user_management')
        );
    }
}