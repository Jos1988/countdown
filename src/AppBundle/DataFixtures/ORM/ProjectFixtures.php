<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Repository\ItemRepository;
use AppBundle\Repository\ProjectRepository;
use DateInterval;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserManager;

class ProjectFixtures extends Fixture
{
    public static $DATA = [
        0 => [
            'title' => 'Feyenoord - SBV Excelsior',
            'description' => 'Thuiswedstrijd in de Kuip tegen SBV Excelsior',
            'ident' => 'abc',
            'user' => 'Jos',
            'items' => [
                0 => [
                    'name' => 'Schouw',
                    'description' => 'Schouw voor de wedstrijd.',
                    'owner' => '00.80',
                    'time' => 'PT1H',
                ],
                1 => [
                    'name' => 'Opkomst personeel',
                    'description' => 'Opkomst bulk personeel.',
                    'owner' => 'PL',
                    'time' => 'PT15M',
                ],
                2 => [
                    'name' => 'Gereed om open te gaan.',
                    'description' => 'Alle sectore gereed om open te gaan.',
                    'owner' => 'Supervisors',
                    'time' => 'PT90M',
                ],
                3 => [
                    'name' => 'Stadion open',
                    'description' => 'Stadion open.',
                    'owner' => 'PL',
                    'time' => 'PT15M',
                ],
                4 => [
                    'name' => 'Aanvang wedstrijd',
                    'description' => 'Fluitsignaal van de scheids.',
                    'owner' => 'PL',
                    'time' => 'PT90M',
                ],
                5 => [
                    'name' => 'Rust',
                    'description' => '',
                    'owner' => '',
                    'time' => 'PT45M',
                ],
                6 => [
                    'name' => 'Einde rust',
                    'description' => '',
                    'owner' => '',
                    'time' => 'PT15M',
                ],
                7 => [
                    'name' => 'Einde wedstrijd',
                    'description' => '',
                    'owner' => '',
                    'time' => 'PT45M',
                ],
            ],
        ],
        1 => [
            'title' => 'Feyenoord - AZ',
            'description' => 'Thuiswedstrijd in de Kuip tegen AZ',
            'ident' => 'def',
            'user' => 'User',
            'items' => [
                0 => [
                    'name' => 'Schouw',
                    'description' => 'Schouw voor de wedstrijd.',
                    'owner' => '00.80',
                    'time' => 'PT1H',
                ],
                1 => [
                    'name' => 'Opkomst personeel',
                    'description' => 'Opkomst bulk personeel.',
                    'owner' => 'PL',
                    'time' => 'PT15M',
                ],
                2 => [
                    'name' => 'Gereed om open te gaan.',
                    'description' => 'Alle sectore gereed om open te gaan.',
                    'owner' => 'Supervisors',
                    'time' => 'PT90M',
                ],
                3 => [
                    'name' => 'Stadion open',
                    'description' => 'Stadion open.',
                    'owner' => 'PL',
                    'time' => 'PT15M',
                ],
                4 => [
                    'name' => 'Aanvang wedstrijd',
                    'description' => 'Fluitsignaal van de scheids.',
                    'owner' => 'PL',
                    'time' => 'PT90M',
                ],
                5 => [
                    'name' => 'Rust',
                    'description' => '',
                    'owner' => '',
                    'time' => 'PT45M',
                ],
                6 => [
                    'name' => 'Einde rust',
                    'description' => '',
                    'owner' => '',
                    'time' => 'PT15M',
                ],
                7 => [
                    'name' => 'Einde wedstrijd',
                    'description' => '',
                    'owner' => '',
                    'time' => 'PT45M',
                ],
            ],
        ],
    ];

    public function load(ObjectManager $manager)
    {
         /** @var ProjectRepository $projectRepo */
        $projectRepo = $this->container->get('countdown.repository.project');
        /** @var ItemRepository $itemRepo */
        $itemRepo = $this->container->get('countdown.repository.item');
        /** @var UserManager $userManager */
        $userManager = $this->container->get('fos_user.user_manager');

        foreach ($this::$DATA as $data) {
            $time = new DateTime('now');

            $project = $projectRepo->create();
            $user = $userManager->findUserByUsername($data['user']);
            $project->setTitle($data['title'])
                ->setDescription($data['description'])
                ->setIdentifier($data['ident'])
                ->setAllowPublicView(true)
                ->setUser($user)
                ->setDate($time);
            $manager->persist($project);

            $time->sub(new DateInterval('PT1H'));

            foreach ($data['items'] as $itemData) {
                $item = $itemRepo->create();
                $item->setName($itemData['name'])
                    ->setDescription($itemData['description'])
                    ->setOwner($itemData['owner'])
                    ->setDeadline($time->format('H:i:s'))
                    ->setProject($project);
                
                $time->add(new DateInterval($itemData['time']));
                $manager->persist($item);
            }
        }

        $manager->flush();
    }
}