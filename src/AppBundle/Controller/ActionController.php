<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Action;
use AppBundle\Services\CountdownService;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @Route("/action/update/{action}", name="countdown_action_update")
     *
     * @param Action           $action
     *
     * @return Response
     * @throws OptimisticLockException
     */
    public function updateAction(
        Action $action
    ) {
        $countdownService = $this->get(CountdownService::class);
        $countdownService->toggleAction($action);

//        dump($action);
//        dump($request);
//        die();

        return new Response();
    }
}
