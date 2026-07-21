<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Form\TodoType;
use App\Repository\TodoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/todo')]
final class TodoController extends AbstractController
{
    #[Route('/', name: 'todo_index', methods: ['GET'])]
    public function index(TodoRepository $todoRepository): Response
    {
        return $this->render('todo/index.html.twig', [
            'todos' => $todoRepository->findBy([], ['id' => 'DESC']),
        ]);
    }

    #[Route('/new', name: 'todo_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $todo = new Todo();
        $todo->setPriority('LOW');
        $todo->setStatus('TODO');

        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($todo);
            $em->flush();

            return $this->redirectToRoute('todo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('todo/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'todo_show', methods: ['GET'])]
    public function show(Todo $todo): Response
    {
        return $this->render('todo/show.html.twig', [
            'todo' => $todo,
        ]);
    }

    #[Route('/{id}/edit', name: 'todo_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Todo $todo, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('todo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('todo/edit.html.twig', [
            'todo' => $todo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'todo_delete', methods: ['POST'])]
    public function delete(Request $request, Todo $todo, EntityManagerInterface $em): Response
    {
        $token = (string) $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete' . $todo->getId(), $token)) {
            $em->remove($todo);
            $em->flush();
        }

        return $this->redirectToRoute('todo_index', [], Response::HTTP_SEE_OTHER);
    }
}
