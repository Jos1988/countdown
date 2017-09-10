<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\Project;
use AppBundle\Form\ProjectType;
use AppBundle\Form\ScheduleType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package Countdownbundle\Controller
 */
class ProjectController extends Controller
{
    /**
     * Create Index
     *
     * @Route("/", name="countdown_index")
     *
     * @return Response
     */
    public function indexAction()
    {
        $projects = $this->container->get('countdown.repository.project')->findAll();

        return $this->render('@App/index.html.twig', ['projects' => $projects]);
    }

    /**
     * View countdown project
     *
     * @Route("/view/{project}", name="countdown_view")
     *
     * @param Project $project
     *
     * @return Response
     */
    public function viewAction(Project $project)
    {
        return $this->render(
            '@App/viewProject.html.twig',
            ['project' => $project, 'endTime' => '23:59:59']
        );
    }

    /**
     * Get time.
     *
     * @Route("/time")
     *
     * @return JsonResponse
     */
    public function getTimeAction()
    {
        $currentTime = $this->get('countdown.service')->getCurrentTime();

        return new JsonResponse(['time' => $currentTime->format('H:i:s')]);
    }

    /**
     * Create schedule.
     *
     * @param Request $request
     * @param Project $project
     *
     * @return Response
     *
     * @Route("/schedule/{project}", name="countdown_schedule")
     */
    public function scheduleAction(
        Request $request,
        Project $project
    ): Response {

        $form = $this->createForm(ScheduleType::class, $project);
        $originalItems = new ArrayCollection();
        foreach ($project->getItems() as $item) {
            $originalItems[] = $item;
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            foreach ($originalItems as $originalItem) {
                if (false === $project->getItems()->contains($originalItem)) {
                    $originalItem->setProject(null);
                    $project->removeItem($originalItem);
                    $entityManager->persist($originalItem);
                    $entityManager->remove($originalItem);
                }
            }

            $this->get('countdown.service')->addProjectToItems($project);
            $this->get('countdown.repository.project')->persist($project, false);
            $entityManager->flush();

            return $this->redirectToRoute('countdown_schedule', ['project' => $project->getId()]);
        }

        return $this->render(
            'AppBundle::schedule.html.twig',
            [
                'project' => $project,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Create or edit project.
     *
     * @Route("/edit/{project}", name="countdown_project_edit")
     * @param Request $request
     * @param Project $project
     *
     * @return Response
     */
    public function EditAction(
        Request $request,
        Project $project = null
    ) {
        $projectRepository = $this->get('countdown.repository.project');

        $title = 'Edit Project';
        if (null == $project) {
            $project = $projectRepository->create();
            $project->setDate(new \DateTime());
            $title = 'New Project';
        }

        $userOptions = $this->getUserOptions();
        $form = $this->createForm(
            ProjectType::class,
            $project,
            [
                'action' => $this->generateUrl('countdown_project_create', ['project' => $project->getId()]),
                'users' => $userOptions,
            ]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Project $project */
            $project = $form->getData();
            if (null !== $this->getUser() && null === $project->getUser()) {
                $project->setUser($this->getUser());
            }

            $projectRepository->persist($project);

            return new JsonResponse(
                [
                    'redirectUrl' => $this->generateUrl('countdown_index'),
                    'status' => 'complete',
                ]
            );
        }

        $form = $this->renderView(
            '@App/Form/createOrEditPartial.html.twig',
            [
                'form' => $form->createView(),
                'title' => $title,
            ]
        );

        return new JsonResponse(
            ['formView' => $form, 'status' => 'incomplete']
        );
    }

    /**
     * Create or edit project.
     *
     * @Route("/create", name="countdown_project_create")
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(
        Request $request
    ) {
        $projectRepository = $this->get('countdown.repository.project');

        $project = $projectRepository->create();
        $project->setDate(new \DateTime());
        $project->setUser($this->getUser());

        $userOptions = $this->getUserOptions();
        $form = $this->createForm(
            ProjectType::class,
            $project,
            [
                'action' => $this->generateUrl('countdown_project_create', ['project' => $project->getId()]),
                'users' => $userOptions,
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Project $project */
            $project = $form->getData();
            $projectRepository->persist($project);

            return $this->redirectToRoute('countdown_index');
        }

        return $this->render(
            '@App/Form/createOrEdit.html.twig',
            ['form' => $form->createView(), 'title' => 'New Project']
        );
    }

    /**
     * Get list of users.
     *
     * @return array
     */
    private function getUserOptions()
    {
        $users = $this->get('app.repository.user')->findAll();
        $userOptions = [];

        foreach ($users as $user) {
            /** @var User $user */
            $userOptions[$user->getUsername()] = $user;
        }

        return $userOptions;
    }

    /**
     * Delete project.
     *
     * @Route("/delete/{project}", name="countdown_delete")
     * @param Project $project
     *
     * @return Response
     */
    public function deleteAction(
        Project $project
    ) {
        $this->get('countdown.service')->setDelete($project);

        return $this->redirectToRoute('countdown_index');
    }
}
