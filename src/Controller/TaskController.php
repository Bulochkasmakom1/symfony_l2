<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/task', name: 'app_task_')]
class TaskController extends AbstractController
{
    //Показ всех задач
    #[Route("/", name: "list")]
    public function list(TaskRepository $taskRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Получаем все задачи
        $allTasks = $taskRepository->findAll();
    
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
    public function view(int $id,TaskRepository $taskRepository): Response
    {
        $task=$taskRepository->find($id);
        
        if(!$task){
            throw $this->createNotFoundException("Task{$id} not found");
        }

        return $this->render('task/view.html.twig', [
            'task'=>$task,
            'category' => $task->getCategory(), 
            'createdAt' => $task->getCreatedAt(),
            'Data' => $task->getData(),
        ]);
    }
    //удаление записи
    #[Route("/delete/{id}", name: "delete")]
    public function delete(int $id,TaskRepository $taskRepository,EntityManagerInterface $entityManagerInterface): Response
    {
        $task=$taskRepository->find($id);

        if(!$task){
            throw $this->createNotFoundException("Task{$id}not found");
        }
            $entityManagerInterface->remove($task);
            $entityManagerInterface->flush();
            $this->addFlash("SUCCES","Task with {$id} succesefull deleted");
            return $this->redirectToRoute('app_task_list');      
    }
//обновление записи
#[Route('/edit/{id}', name: 'edit')]
public function edit(Request $request, EntityManagerInterface $entityManager, int $id): Response
{
    $task = $entityManager->getRepository(Task::class)->find($id);

    if (!$task) {
        throw $this->createNotFoundException('Задача с ID ' . $id . ' не найдена');
    }

    $form = $this->createForm(TaskType::class, $task);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

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
            $task=$form->getData();
            $entityManagerInterface->persist($task);
            $entityManagerInterface->flush();
            $this->addFlash('SUCCES',"Task with succesfully delete");
            return $this->redirectToRoute('app_task_list');
        }

        return $this->render('task/create.html.twig', [
            'task_form'=>$form
        ]);
    }
}
