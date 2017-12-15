<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Action;
use AppBundle\Entity\Project;
use AppBundle\Services\CountdownService;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package AppBundle\Controller
 */
class ActionController extends Controller
{
    /**
     * Create Item route.
     * @Route("/action/push/{action}/{newStatus}", name="countdown_action_push")
     *
     * @param Action $action
     * @param string $newStatus
     *
     * @return Response
     * @throws Exception
     * @throws OptimisticLockException
     */
    public function pushUpdateAction(Action $action, string $newStatus)
    {
        if ($action->getItem()->getProject()->getUser() !== $this->getUser()) {
            throw new Exception('User does not have access to this project.', 403);
        }

        $this->get(CountdownService::class)->setActionCompleted($action, $newStatus);

        return new Response();
    }

    /**
     * Gets updated actions for current project.
     * @Route("/action/pull/{project}/{lastUpdate}", name="countdown_action_pull")
     *
     * @param Project $project
     * @param int     $lastUpdate
     *
     * @return Response
     * @throws Exception
     */
    public function pullUpdatedAction(Project $project, int $lastUpdate)
    {
        if ($project->getUser() !== $this->getUser()) {
            throw new Exception('User does not have access to this project.', 403);
        }

        $actions = $this->get(CountdownService::class)->getUpdatedActions($project, $lastUpdate);

        $responseData = [];
        foreach ($actions as $action) {
            $responseData[$action->getId()] = $action->isCompleted();
        }

        return new JsonResponse($responseData);
    }
}
