<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserManager;

class UserFixtures extends Fixture
{

    static $DATA = [
        'Jos' => [
            'username' => 'Jos',
            'email' => 'josja_benard@hotmail.com',
            'password' => 'apollo010',
            'roles' => ['ROLE_ADMIN'],
        ],
        'User' => [
            'username' => 'User',
            'email' => 'user@test.com',
            'password' => 'apollo010',
            'roles' => ['ROLE_USER'],
        ],
    ];

    public function load(ObjectManager $manager)
    {
        /** @var UserManager $userManager */
        $userManager = $this->container->get('fos_user.user_manager');

        foreach ($this::$DATA as $userData) {
            $user = $userManager->createUser();
            $user->setUsername($userData['username'])
                ->setEmail($userData['email'])
                ->setPlainPassword($userData['password'])
                ->setEnabled(true);

            foreach ($userData['roles'] as $role) : $user->addRole($role); endforeach;

            $userManager->updateUser($user);
        }
    }
}