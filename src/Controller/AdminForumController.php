<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Discussion;
use App\Entity\Message;
use App\Form\CategoryType;
use App\Form\DiscussionType;
use App\Form\MessageType;
use App\Repository\CategoryRepository;
use App\Repository\DiscussionRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminForumController extends AbstractController
{
    #[Route('/', name: 'admin_dashboard', methods: ['GET'])]
    public function dashboard(
        DiscussionRepository $discussionRepository,
        MessageRepository $messageRepository,
        CategoryRepository $categoryRepository
    ): Response {
        $totalDiscussions = count($discussionRepository->findAll());
        $totalMessages = count($messageRepository->findAll());
        $totalCategories = count($categoryRepository->findAll());
        $latestDiscussions = $discussionRepository->findLatest(5);
        $latestMessages = $messageRepository->findLatest(5);

        return $this->render('admin/dashboard.html.twig', [
            'total_discussions' => $totalDiscussions,
            'total_messages' => $totalMessages,
            'total_categories' => $totalCategories,
            'latest_discussions' => $latestDiscussions,
            'latest_messages' => $latestMessages,
        ]);
    }

    // Category CRUD
    #[Route('/categories', name: 'admin_forum_categories', methods: ['GET', 'POST'])]
    public function categories(
        Request $request,
        CategoryRepository $categoryRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash('success', 'Category created successfully!');
            return $this->redirectToRoute('admin_forum_categories');
        }

        $categories = $categoryRepository->findAll();

        return $this->render('admin/category/index.html.twig', [
            'categories' => $categories,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/category/{id}/edit', name: 'admin_forum_category_edit', methods: ['GET', 'POST'])]
    public function editCategory(
        Request $request,
        Category $category,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Category updated successfully!');
            return $this->redirectToRoute('admin_forum_categories');
        }

        return $this->render('admin/category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/category/{id}/delete', name: 'admin_forum_category_delete', methods: ['POST'])]
    public function deleteCategory(
        Request $request,
        Category $category,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager->remove($category);
            $entityManager->flush();
            $this->addFlash('success', 'Category deleted successfully!');
        }

        return $this->redirectToRoute('admin_forum_categories');
    }

    // Discussion CRUD
    #[Route('/discussions', name: 'admin_forum_discussions', methods: ['GET'])]
    public function discussions(DiscussionRepository $discussionRepository): Response
    {
        $discussions = $discussionRepository->findAll();

        return $this->render('admin/discussion/index.html.twig', [
            'discussions' => $discussions,
        ]);
    }

    #[Route('/discussion/{id}/edit', name: 'admin_forum_discussion_edit', methods: ['GET', 'POST'])]
    public function editDiscussion(
        Request $request,
        Discussion $discussion,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(DiscussionType::class, $discussion, ['is_admin' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Discussion updated successfully!');
            return $this->redirectToRoute('admin_forum_discussions');
        }

        return $this->render('admin/discussion/edit.html.twig', [
            'discussion' => $discussion,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/discussion/{id}/delete', name: 'admin_forum_discussion_delete', methods: ['POST'])]
    public function deleteDiscussion(
        Request $request,
        Discussion $discussion,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete'.$discussion->getId(), $request->request->get('_token'))) {
            $entityManager->remove($discussion);
            $entityManager->flush();
            $this->addFlash('success', 'Discussion deleted successfully!');
        }

        return $this->redirectToRoute('admin_forum_discussions');
    }

    // Message CRUD
    #[Route('/messages', name: 'admin_forum_messages', methods: ['GET'])]
    public function messages(MessageRepository $messageRepository): Response
    {
        $messages = $messageRepository->findAll();

        return $this->render('admin/message/index.html.twig', [
            'messages' => $messages,
        ]);
    }

    #[Route('/message/{id}/edit', name: 'admin_forum_message_edit', methods: ['GET', 'POST'])]
    public function editMessage(
        Request $request,
        Message $message,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(MessageType::class, $message, ['is_admin' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Message updated successfully!');
            return $this->redirectToRoute('admin_forum_messages');
        }

        return $this->render('admin/message/edit.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/message/{id}/delete', name: 'admin_forum_message_delete', methods: ['POST'])]
    public function deleteMessage(
        Request $request,
        Message $message,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete'.$message->getId(), $request->request->get('_token'))) {
            $entityManager->remove($message);
            $entityManager->flush();
            $this->addFlash('success', 'Message deleted successfully!');
        }

        return $this->redirectToRoute('admin_forum_messages');
    }
}
