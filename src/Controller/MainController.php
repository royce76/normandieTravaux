<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Task;
use App\Entity\Project;
use App\Entity\Archive;
use App\Repository\ProjectRepository;
use App\Repository\ArchiveRepository;
use App\Repository\TaskRepository;
use App\Form\ProjectType;
use App\Form\UpdateProjectType;
use App\Form\UpdateTaskType;
use App\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 *
 * @IsGranted("ROLE_USER")
 */
class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(ProjectRepository $projectRepository): Response
    {
      $user = $this->getUser();
      $projects = $projectRepository->getProjectsUser($user->getId());
      return $this->render('main/index.html.twig', [
           'projects' => $projects,
       ]);
    }

    /**
     * @Route("/project/{id}", name="app_project", requirements={"id"="\d+"})
     */
    public function project(int $id,ProjectRepository $projectRepository): Response
    {
        $project = $projectRepository->getProjectTasksUser($id);
        return $this->render('main/project.html.twig', [
            'projects' => $project,
        ]);
    }

    /**
     * @Route("/project_new", name="add_project")
     */
    public function newProject(Request $request, ValidatorInterface $validator): Response
    {
      $errors = [];
      $project = new Project();
      $form = $this->createForm(ProjectType::class, $project);
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        $project->setUser($this->getUser());
        $project->setCreationDate(new \DateTime('now'));
        $project->setStatus('En cours');
        $errors = $validator->validate($project);
        if (count($errors) == 0) {
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($project);
          $entityManager->flush();
          $this->addFlash(
            'success',
            'Projet ajouté.'
          );
          return $this->redirectToRoute('app_home');
        }
      }

      return $this->render('main/project_new.html.twig', [
           'errors' => $errors,
           'projectForm' => $form->createView(),
       ]);
    }

    /**
     * @Route("/task_new/{id}", name="add_task", requirements={"id"="\d+"})
     */
    public function newtask(int $id, ProjectRepository $projectRepository, Request $request, ValidatorInterface $validator): Response
    {
      $errors = [];
      $project = $projectRepository->find($id);
      $task = new task();
      $form = $this->createForm(TaskType::class, $task);
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        $task->setUser($this->getUser());
        $task->setProject($project);
        $task->setCreationDate(new \DateTime('now'));
        $task->setStatus('En cours');
        $errors = $validator->validate($task);
        if (count($errors) == 0) {
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($task);
          $entityManager->flush();
          $this->addFlash(
            'success',
            'Projet ajouté.'
          );
          return $this->redirectToRoute('app_project', ['id' => $project->getId()]);
        }
      }

      return $this->render('main/task_new.html.twig', [
           'errors' => $errors,
           'project' => $project,
           'taskForm' => $form->createView(),
       ]);
    }

    /**
     * @Route("/{id}", name="project_delete", requirements={"id"="\d+"})
     */
    public function deleteProject(int $id, ProjectRepository $projectRepository): Response
    {
        $project = $projectRepository->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($project);
        $entityManager->flush();


        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/task/{id}", name="task_delete", requirements={"id"="\d+"})
     */
    public function deleteTask(int $id, TaskRepository $taskRepository): Response
    {
        $task = $taskRepository->find($id);
        $project = $task->getProject();
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($task);
        $entityManager->flush();


        return $this->redirectToRoute('app_project', ['id' => $project->getId()]);
    }

    /**
     * @Route("/project_update/{id}", name="update_project", requirements={"id"="\d+"})
     */
    public function updateProject(int $id, ProjectRepository $projectRepository, Request $request, ValidatorInterface $validator): Response
    {
      $errors = [];
      $project = $projectRepository->find($id);
      $form = $this->createForm(UpdateProjectType::class, $project);
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {

        $errors = $validator->validate($project);
        if (count($errors) == 0) {
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->flush();
          $this->addFlash(
            'success',
            'Projet modifié.'
          );
          return $this->redirectToRoute('app_project', ['id' => $project->getId()]);
        }
      }

      return $this->render('main/project_update.html.twig', [
           'errors' => $errors,
           'project' => $project,
           'projectForm' => $form->createView(),
       ]);
    }

    /**
     * @Route("/task_update/{id}", name="update_task", requirements={"id"="\d+"})
     */
    public function updateTask(int $id, TaskRepository $taskRepository, Request $request, ValidatorInterface $validator): Response
    {
      $errors = [];
      $task = $taskRepository->find($id);
      $project = $task->getProject();
      $form = $this->createForm(UpdateTaskType::class, $task);
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {

        $errors = $validator->validate($task);
        if (count($errors) == 0) {
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->flush();
          $this->addFlash(
            'success',
            'Tâche modifié.'
          );
          return $this->redirectToRoute('app_project', ['id' => $project->getId()]);
        }
      }

      return $this->render('main/task_update.html.twig', [
           'errors' => $errors,
           'project' => $project,
           'taskForm' => $form->createView(),
       ]);
    }

    /**
     * @Route("/archive/{id}", name="project_archive", requirements={"id"="\d+"})
     */
    public function archiveProject(int $id, ProjectRepository $projectRepository): Response
    {
        $project = $projectRepository->getProjectTasksUser($id);
        $archive = new Archive();
        $archive->setProjectArchive($project);
        $archive->setUser($this->getUser());
        dump($archive);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($archive);
        foreach ($project as $value) {
          $entityManager->remove($value);
        }
        $entityManager->flush();

        return $this->redirectToRoute('app_home');

    }

    /**
     * @Route("/archive", name="app_archive")
     */
    public function archive(ArchiveRepository $archiveRepository)
    {
      $listArchive = $archiveRepository->findAll();
      dump($listArchive);

      return $this->render('main/archive.html.twig', [
          'listArchive' => $listArchive,
      ]);
    }

    /**
     * @Route("/archives/{id}", name="app_archive_id", requirements={"id"="\d+"})
     */
    public function showArchive(int $id, ArchiveRepository $archiveRepository)
    {
      $listArchives = $archiveRepository->find($id);
      dump($listArchives);

      return $this->render('main/show_archive.html.twig', [
          'listArchives' => $listArchives,
      ]);
    }

    /**
     * @Route("/desarchive/{id}", name="app_desarchive", requirements={"id"="\d+"})
     */
    public function desarchive(int $id, ArchiveRepository $archiveRepository)
    {
      $archive = $archiveRepository->find($id);
      $project_archive = $archive->getProjectArchive();
      dump($project_archive);


      foreach ($project_archive as $project) {
        $back_project = new Project();
        $back_project->setName($project->getName());
        $back_project->setDescription($project->getDescription());
        $back_project->setCreationDate($project->getCreationDate());
        $back_project->setDeadline($project->getDeadline());
        $back_project->setStatus($project->getStatus());
        $back_project->setUser($this->getUser());

        $tasks = $project->getTasks();
        foreach ($tasks as $task) {
          $back_task = new Task();
          $back_task->setName($task->getName());
          $back_task->setDescription($task->getDescription());
          $back_task->setCreationDate($task->getCreationDate());
          $back_task->setDeadline($task->getDeadline());
          $back_task->setStatus($task->getStatus());
          $back_task->setUser($this->getUser());
          $back_task->setProject($back_project);
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($back_task);
        }
      }
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->persist($back_project);
      $entityManager->remove($archive);
      $entityManager->flush();
      return $this->redirectToRoute('app_project', ['id' => $back_project->getId()]);
    }

}
