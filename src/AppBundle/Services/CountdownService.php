<?php

namespace AppBundle\Services;

use AppBundle\Entity\Action;
use AppBundle\Entity\Project;
use AppBundle\Repository\ActionRepository;
use AppBundle\Repository\ItemRepository;
use AppBundle\Repository\ProjectRepository;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\OptimisticLockException;

/**
 * Class CountdownService
 *
 * @package AppBundle\Services
 */
class CountdownService
{
    /** @var ProjectRepository */
    private $projectRepository;

    /** @var ItemRepository */
    private $itemRepository;

    /** @var ActionRepository */
    private $actionRepository;

    /** @var DateTimeZone */
    private $timezone;

    /**
     * CountdownService constructor.
     *
     * @param ProjectRepository $projectRepository
     * @param ItemRepository    $itemRepository
     * @param ActionRepository  $actionRepository
     * @param string            $timeZone
     */
    public function __construct(
        ProjectRepository $projectRepository,
        ItemRepository $itemRepository,
        ActionRepository $actionRepository,
        string $timeZone
    ) {
        $this->projectRepository = $projectRepository;
        $this->itemRepository = $itemRepository;
        $this->actionRepository = $actionRepository;
        $this->timezone = new DateTimeZone($timeZone);
    }

    /**
     * Set project for deletion.
     *
     * @param Project $project
     *
     * @throws OptimisticLockException
     */
    public function setDelete(Project $project)
    {
        $project->setDeleted(true);

        $this->projectRepository->persist($project);
    }

    /**
     * Return Current time.
     *
     * @return DateTime
     */
    public function getCurrentTime(): DateTime
    {
        $currentTime = new DateTime();
        $currentTime->setTimezone($this->timezone);

        return $currentTime;
    }

    /**
     * @param Action $action
     *
     * @throws OptimisticLockException
     */
    public function toggleAction(Action $action)
    {
        $action->setCompleted(!$action->isCompleted());

        $this->actionRepository->persist($action);
    }
}
