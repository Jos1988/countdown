<?php

namespace AppBundle\Services;

use AppBundle\Entity\Action;
use AppBundle\Entity\Project;
use AppBundle\Entity\User;
use AppBundle\Repository\ActionRepository;
use AppBundle\Repository\ItemRepository;
use AppBundle\Repository\ProjectRepository;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\Config\Definition\Exception\Exception;

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
     * Check if user is authorized to view/edit project.
     *
     * @param User    $user
     * @param Project $project
     */
    public function checkAuthorized(user $user, Project $project)
    {
        if ($project->getUser() !== $user) {
            throw new Exception('User does not have access to this project.', 403);
        }
    }

    /**
     * @param Action $action
     *
     * @param string $completed
     *
     * @throws OptimisticLockException
     */
    public function setActionStatus(Action $action, string $completed)
    {
        if ($completed === 'true') {
            $completed = true;
        } elseif ($completed === 'false') {
            $completed = false;
        } else {
            throw new Exception('Unkown \'completed\' status' . $completed . '.', 404);
        }

        $action->setCompleted($completed)
            ->setUpdated(new DateTime());

        $project = $action->getItem()->getProject();
        $project->setLastUpdate(new DateTime('now'));

        $this->actionRepository->persist($action, false);
        $this->projectRepository->persist($project, false);
        $this->projectRepository->flushAll();
    }

    /**
     * Get project actions updated since timestamp.
     *
     * @param Project $project
     * @param int     $lastUpdatedStamp
     *
     * @return Action[]
     */
    public function getUpdatedActions(Project $project, int $lastUpdatedStamp)
    {
        $lastUpdated = new DateTime();
        $lastUpdated->setTimestamp($lastUpdatedStamp);

        return $this->actionRepository->getUpdatedActions($project, $lastUpdated);
    }
}
