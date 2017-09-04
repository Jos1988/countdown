<?php

namespace AppBundle\Services;

use AppBundle\Entity\Item;
use AppBundle\Entity\Project;
use AppBundle\Repository\ItemRepository;
use AppBundle\Repository\ProjectRepository;
use DateTime;
use DateTimeZone;

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

    /** @var DateTimeZone */
    private $timezone;

    /**
     * CountdownService constructor.
     *
     * @param ProjectRepository $projectRepository
     * @param ItemRepository    $itemRepository
     * @param string            $timeZone
     */
    public function __construct(ProjectRepository $projectRepository, ItemRepository $itemRepository, string $timeZone)
    {
        $this->projectRepository = $projectRepository;
        $this->itemRepository = $itemRepository;
        $this->timezone = new DateTimeZone($timeZone);
    }

    /**
     * Set project for deletion.
     *
     * @param Project $project
     */
    public function setDelete(Project $project)
    {
        $project->setDeleted(true);

        $this->projectRepository->persist($project);
    }

    /**
     * @param Project $project
     */
    public function addProjectToItems(Project $project)
    {
        foreach ($project->getItems() as $item) {
            $item->setProject($project);
        }
    }

    /**
     * Return Current time.
     *
     * @return DateTime
     */
    public function getCurrentTime():DateTime
    {
        $currentTime = new DateTime();
        $currentTime->setTimezone($this->timezone);

        return $currentTime;
    }
}
