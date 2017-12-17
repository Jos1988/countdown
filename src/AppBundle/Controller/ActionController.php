<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Action;
use AppBundle\Entity\Project;
use AppBundle\Services\CountdownService;
use DateTime;
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
        $countdownService = $this->get(CountdownService::class);
        $countdownService->checkAuthorized($this->getUser(), $action->getItem()->getProject());
        $countdownService->setActionStatus($action, $newStatus);

        return new Response();
    }

    /**
     * Gets updated actions for current project.\
     *
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
        $countdownService = $this->get(CountdownService::class);
        $countdownService->checkAuthorized($this->getUser(), $project);
        //Safety margin to handle updates in browser multiple times.
        $updateLag = $this->getParameter('update_lag_sec');

        if (null !== $project->getLastUpdate() &&
            $lastUpdate - $updateLag <= $project->getLastUpdate()->getTimestamp()) {
            $actions = $countdownService->getUpdatedActions(
                $project,
                $lastUpdate - $updateLag
            );
            $newLastUpdate = new DateTime('now');

            $responseData = [
                'interval' => $this->getParameter('update_interval_ms'),
                'newLastUpdate' => $newLastUpdate->getTimestamp(),
            ];
            foreach ($actions as $action) {
                $responseData[$action->getId()] = $action->isCompleted();
            }

            return new JsonResponse($responseData);
        }

        return new Response();
    }
}
