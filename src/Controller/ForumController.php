<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Discussion;
use App\Entity\Message;
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

#[Route('/forum')]
class ForumController extends AbstractController
{
    #[Route('/', name: 'app_forum_index', methods: ['GET'])]
    public function index(DiscussionRepository $discussionRepository): Response
    {
        $latestDiscussions = $discussionRepository->findLatest(10);
        $mostViewedDiscussions = $discussionRepository->findMostViewed(5);

        return $this->render('forum/index.html.twig', [
            'latest_discussions' => $latestDiscussions,
            'most_viewed_discussions' => $mostViewedDiscussions,
        ]);
    }

    #[Route('/discussions', name: 'app_forum_discussions', methods: ['GET'])]
    public function discussions(
        Request $request,
        DiscussionRepository $discussionRepository,
        CategoryRepository $categoryRepository
    ): Response {
        $search = $request->query->get('search');
        $categoryId = $request->query->get('category');
        $category = $categoryId ? $categoryRepository->find($categoryId) : null;

        if ($search) {
            $discussions = $discussionRepository->findBySearch($search);
        } else {
            $discussions = $discussionRepository->findByCategory($category);
        }

        $categories = $categoryRepository->findAll();

        return $this->render('forum/discussions.html.twig', [
            'discussions' => $discussions,
            'categories' => $categories,
            'selected_category' => $category,
            'search' => $search,
        ]);
    }

    #[Route('/discussions/add', name: 'app_forum_discussion_add', methods: ['GET', 'POST'])]
    public function addDiscussion(
        Request $request,
        CategoryRepository $categoryRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $discussion = new Discussion();
        $form = $this->createForm(DiscussionType::class, $discussion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Set createdAt if not already set
            if ($discussion->getCreatedAt() === null) {
                $discussion->setCreatedAt(new \DateTime());
            }
            
            $entityManager->persist($discussion);
            $entityManager->flush();
            $this->addFlash('success', 'Discussion created successfully!');
            return $this->redirectToRoute('app_forum_discussions');
        }

        $categories = $categoryRepository->findAll();

        return $this->render('forum/add_discussion.html.twig', [
            'form' => $form->createView(),
            'categories' => $categories,
        ]);
    }

    #[Route('/discussion/{id}', name: 'app_forum_discussion_show', methods: ['GET', 'POST'])]
    public function showDiscussion(
        Discussion $discussion,
        Request $request,
        MessageRepository $messageRepository,
        EntityManagerInterface $entityManager,
        DiscussionRepository $discussionRepository
    ): Response {
        // Increment views
        $discussionRepository->incrementViews($discussion);

        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setDiscussion($discussion);
            $entityManager->persist($message);
            $entityManager->flush();
            $this->addFlash('success', 'Message posted successfully!');
            return $this->redirectToRoute('app_forum_discussion_show', ['id' => $discussion->getId()]);
        }

        $messages = $messageRepository->findByDiscussion($discussion);

        return $this->render('forum/discussion_show.html.twig', [
            'discussion' => $discussion,
            'messages' => $messages,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/categories', name: 'app_forum_categories', methods: ['GET'])]
    public function categories(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('forum/categories.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/category/{id}', name: 'app_forum_category_show', methods: ['GET'])]
    public function showCategory(Category $category, DiscussionRepository $discussionRepository): Response
    {
        $discussions = $discussionRepository->findByCategory($category);

        return $this->render('forum/category_show.html.twig', [
            'category' => $category,
            'discussions' => $discussions,
        ]);
    }

    #[Route('/test-no-html5', name: 'app_forum_test_no_html5', methods: ['GET'])]
    public function testNoHtml5(Request $request, DiscussionRepository $discussionRepository, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager): Response
    {
        $discussion = new Discussion();
        $form = $this->createForm(DiscussionType::class, $discussion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($discussion->getCreatedAt() === null) {
                $discussion->setCreatedAt(new \DateTime());
            }
            
            $entityManager->persist($discussion);
            $entityManager->flush();
            $this->addFlash('success', 'Form submitted successfully with server-side validation only!');
            return $this->redirectToRoute('app_forum_test_no_html5');
        }

        return $this->render('forum/test-no-html5.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/validation-test', name: 'app_forum_validation_test', methods: ['GET'])]
    public function validationTest(): Response
    {
        return $this->render('forum/validation_test.html.twig');
    }

    #[Route('/test', name: 'app_forum_test', methods: ['GET'])]
    public function test(): Response
    {
        return $this->render('forum/test.html.twig');
    }

    #[Route('/about', name: 'app_forum_about', methods: ['GET'])]
    public function about(): Response
    {
        return $this->render('forum/about.html.twig');
    }

    #[Route('/contact', name: 'app_forum_contact', methods: ['GET'])]
    public function contact(): Response
    {
        return $this->render('forum/contact.html.twig');
    }
}
