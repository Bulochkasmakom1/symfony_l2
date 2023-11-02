<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Services\TaskService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[Route('/task', name: 'app_task_')]
class TaskController extends AbstractController
{
    //Показ всех задач
    #[Route("/", name: "list")]
    public function list(PaginatorInterface $paginator, Request $request, TaskService $taskService): Response
    {
        // Получаем все задачи
        $allTasks = $taskService->listTask();
    
        // Пагинируем результаты
        $pagination = $paginator->paginate(
            $allTasks, // Все задачи
            $request->query->getInt('page', 1), // Номер страницы
            2 // Количество элементов на странице
        );
    
        return $this->render("task/list.html.twig", [
            'pagination' => $pagination,
        ]);
    }
    //подробнее о каждой записи
    #[Route("/view/{id}", name: "view")]
    public function view(int $id,TaskService $taskService): Response
    {
        $task=$taskService->viewTask($id);
        

        return $this->render('task/view.html.twig', [
            'task'=>$task,
            'category' => $task->getCategory(), 
            'createdAt' => $task->getCreatedAt(),
            'Data' => $task->getData(),
        ]);
    }
    //удаление записи
    #[Route("/delete/{id}", name: "delete")]
    public function delete(int $id,TaskService $taskService,EntityManagerInterface $entityManagerInterface): Response
    {
        $task=$taskService->deleteTask($id);

            $this->addFlash("SUCCES","Task with {$id} succesefull deleted");
            return $this->redirectToRoute('app_task_list');      
    }
//обновление записи
#[Route('/edit/{id}', name: 'edit')]
public function edit(Request $request, EntityManagerInterface $entityManager, int $id): Response
{
    $task = $entityManager->getRepository(Task::class)->find($id);

    $form = $this->createForm(TaskType::class, $task);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        return $this->redirectToRoute('app_task_list');
    }

    return $this->render('task/edit.html.twig', [
        'form_edit' => $form->createView(),
    ]);
}
    //создание записи
    #[Route('/create', name: 'create')]
    public function index(Request  $request ,EntityManagerInterface $entityManagerInterface): Response
    {
        $task=new Task;

        $form=$this->createForm(TaskType::class,$task);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->addFlash('SUCCES',"Task with succesfully delete");
            return $this->redirectToRoute('app_task_list');
        }

        return $this->render('task/create.html.twig', [
            'task_form'=>$form
        ]);
    }
}
