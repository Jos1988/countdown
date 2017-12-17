<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Project;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;

/**
 * ProjectRepository
 */
class ProjectRepository extends EntityRepository
{
    /**
     * Create new project.
     *
     * @return Project
     */
    public function create(): Project
    {
        return new Project();
    }

    /**
     * Persist Project.
     *
     * @param Project $project
     * @param bool    $flush
     *
     * @throws OptimisticLockException
     */
    public function persist(Project $project, $flush = true)
    {
        $this->getEntityManager()->persist($project);
        if ($flush) {
            $this->getEntityManager()->flush($project);
        }
    }

    /**
     * Flush all entities.
     *
     * @throws OptimisticLockException
     */
    public function flushAll()
    {
        $this->getEntityManager()->flush();
    }
}
