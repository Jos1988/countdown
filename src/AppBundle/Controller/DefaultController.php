<?php

namespace AppBundle\Controller;

use AppBundle\Entity\App;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package AppBundle\Controller
 *
 * @Route(host="hammer.dev")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        return $this->render('@App/index.html.twig', ['menuStatus' => $request->query->get('menu-status')]);
    }

    /**
     * @Route("/app/{app}", name="appPage")
     * @param Request $request
     * @param App     $app
     *
     * @return Response
     */
    public function appPage(Request $request, App $app)
    {
        return $this->render(
            '@App/app.html.twig',
            [
                'app' => $app,
                'menuStatus' => $request->query->get('menu-status')
            ]
        );
    }
}
