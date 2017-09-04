<?php

namespace CountdownBundle\Repository;

use CountdownBundle\Entity\Project;
use Doctrine\ORM\EntityRepository;

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
     */
    public function persist(Project $project, $flush = true)
    {
        $this->getEntityManager()->persist($project);
        if ($flush) {
            $this->getEntityManager()->flush($project);
        }
    }
}
