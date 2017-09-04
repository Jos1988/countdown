<?php

namespace CountdownBundle\Controller;

use CountdownBundle\Entity\Item;
use CountdownBundle\Entity\Project;
use CountdownBundle\Form\ItemType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package Countdownbundle\Controller
 *
 * @Route(host="countdown.dev")
 */
class ItemController extends Controller
{
    /**
     * Create Item route.
     * @Route("/item/create/{project}", name="countdown_item_create")
     *
     * @param Request $request
     * @param Project $project
     *
     * @return Response
     */
    public function createAction(
        Request $request,
        Project $project
    ) {
        return $this->createOrEditItem($request, 'create', $project, null);
    }

    /**
     * Edit Item route.
     * @Route("/item/{project}/edit/{item}", name="countdown_item_edit")
     *
     * @param Request $request
     * @param Project $project
     * @param Item    $item
     *
     * @return Response
     */
    public function editAction(
        Request $request,
        Project $project,
        Item $item
    ) {
        return $this->createOrEditItem($request, 'edit', $project, $item);
    }

    /**
     * Create or edit Item.
     * @param Request $request
     * @param string  $action
     * @param Project $project
     * @param Item    $item
     *
     * @return Response
     */
    private function createOrEditItem(
        Request $request,
        $action,
        Project $project,
        Item $item = null
    ) {
        $itemRepository = $this->get('countdown.repository.item');
        $countdownService = $this->get('countdown.service');
        $title = 'Edit Item';
        $first = false;
        if ('create' == $action) {
            $item = $itemRepository->create();
            $title = 'New Item';
            if (0 === $project->getItems()->count()) {
                $first = true;
            }
        }

        $form = $this->createForm(
            ItemType::class,
            $item,
            [
                'endTimes' => $countdownService->getEndTimes($project),
            ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $projectRepository = $this->get('countdown.repository.project');

            /** @var Item $item */
            $item = $form->getData();
            $item->setProject($project);
            $item = $countdownService->setDateTimesToDate($item, $project->getDate());
            $project = $countdownService->moveFollowingItemsDownTimeLine($project, $item);
            $itemRepository->persist($item);
            $project->addItem($item);
            $projectRepository->persist($project);

            return $this->redirectToRoute('countdown_schedule', ['project' => $project->getId()]);
        }

        return $this->render(
            '@Countdown/Form/createOrEditItem.html.twig',
            [
                'project' => $project,
                'form' => $form->createView(),
                'title' => $title,
                'firstItem' => $first,
            ]
        );
    }
}
