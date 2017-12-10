<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Action;
use AppBundle\Entity\Item;
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
            'start' => 'PT30M',
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
            'start' => 'PT30M',
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
            $time->sub(new DateInterval('PT1H'));
            $start = clone $time;
            $project = $projectRepo->create();
            $user = $userManager->findUserByUsername($data['user']);
            $project->setTitle($data['title'])
                ->setDescription($data['description'])
                ->setIdentifier($data['ident'])
                ->setAllowPublicView(true)
                ->setUser($user)
                ->setDate($start->sub(new DateInterval($data['start'])));
            $manager->persist($project);

            foreach ($data['items'] as $itemData) {
                $item = $this->createItem($itemRepo, $time, $itemData);
                $item->setProject($project);

                for ($c = rand(0, 10); $c > 0; $c--) {
                    $action = $this->createRandomAction();
                    $action->setItem($item);
                    $manager->persist($action);
                    $item->addAction($action);
                }

                $manager->persist($item);
            }

            $manager->persist($project);
        }

        $manager->flush();
    }

    /**
     * Create Item
     *
     * @param ItemRepository $itemRepository
     * @param DateTime       $time
     * @param array          $itemData
     *
     * @return Item
     */
    private function createItem(ItemRepository $itemRepository, DateTime $time, array $itemData)
    {
        $item = $itemRepository->create();
        $item->setName($itemData['name'])
            ->setDescription($itemData['description'])
            ->setOwner($itemData['owner'])
            ->setDeadline(clone $time);

        $time->add(new DateInterval($itemData['time']));

        return $item;
    }

    /**
     * Creates a random action
     *
     * @return Action
     */
    private function createRandomAction()
    {
        $action = new Action();
        $description = $this->getRandomWord(rand(5, 10)).
            $this->getRandomWord(rand(5, 10)).
            $this->getRandomWord(rand(5, 10)).
            $this->getRandomWord(rand(5, 10)).
            '.';

        $action->setName($this->getRandomWord())
            ->setDescription($description);

        return $action;
    }

    /**
     * get Random word.
     *
     * Credits: https://stackoverflow.com/users/311744/benjamin-crouzier
     *
     * @param int $len
     *
     * @return bool|string
     */
    function getRandomWord($len = 5)
    {
        $word = array_merge(range('a', 'z'), range('A', 'Z'));
        shuffle($word);

        return substr(implode($word), 0, $len);
    }
}