<?php

namespace App\Controller\Admin;

use App\Entity\ForumCategory;
use App\Entity\ForumDiscussion;
use App\Entity\ForumMessage;
use App\Form\ForumCategoryType;
use App\Form\ForumDiscussionType;
use App\Repository\ForumCategoryRepository;
use App\Repository\ForumDiscussionRepository;
use App\Repository\ForumMessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/forum')]
#[IsGranted('ROLE_ADMIN')]
class AdminForumController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }
    // ========== CATEGORIES ==========

    #[Route('/categories', name: 'admin_forum_categories')]
    public function categories(ForumCategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('admin/forum/category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/category/new', name: 'admin_forum_category_new')]
    public function newCategory(Request $request): Response
    {
        $category = new ForumCategory();
        $form = $this->createForm(ForumCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($category);
            $this->entityManager->flush();

            $this->addFlash('success', 'Category created successfully.');
            return $this->redirectToRoute('admin_forum_categories');
        }

        return $this->render('admin/forum/category/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/category/{id}/edit', name: 'admin_forum_category_edit')]
    public function editCategory(
        ForumCategory $category,
        Request $request
    ): Response {
        $form = $this->createForm(ForumCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Category updated successfully.');
            return $this->redirectToRoute('admin_forum_categories');
        }

        return $this->render('admin/forum/category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/category/{id}/delete', name: 'admin_forum_category_delete', methods: ['POST'])]
    public function deleteCategory(
        ForumCategory $category,
        Request $request
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            try {
                $this->entityManager->remove($category);
                $this->entityManager->flush();
                $this->addFlash('success', 'Category deleted successfully.');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Unable to delete category. It may contain discussions that need to be removed first.');
            }
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('admin_forum_categories');
    }

    // ========== DISCUSSIONS ==========

    #[Route('/discussions', name: 'admin_forum_discussions')]
    public function discussions(ForumDiscussionRepository $discussionRepository): Response
    {
        $discussions = $discussionRepository->findBy(
            [],
            ['updatedAt' => 'DESC']
        );

        return $this->render('admin/forum/discussion/index.html.twig', [
            'discussions' => $discussions,
        ]);
    }

    #[Route('/discussion/new', name: 'admin_forum_discussion_new')]
    public function newDiscussion(Request $request): Response
    {
        $discussion = new ForumDiscussion();
        // Set current user as author
        $discussion->setAuthor($this->getUser());
        
        $form = $this->createForm(ForumDiscussionType::class, $discussion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($discussion);
            $this->entityManager->flush();

            $this->addFlash('success', 'Discussion created successfully.');
            return $this->redirectToRoute('admin_forum_discussions');
        }

        return $this->render('admin/forum/discussion/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/discussion/{id}/edit', name: 'admin_forum_discussion_edit')]
    public function editDiscussion(
        ForumDiscussion $discussion,
        Request $request
    ): Response {
        $form = $this->createForm(ForumDiscussionType::class, $discussion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $this->addFlash('success', 'Discussion updated successfully.');
            return $this->redirectToRoute('admin_forum_discussions');
        }

        return $this->render('admin/forum/discussion/edit.html.twig', [
            'discussion' => $discussion,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/discussion/{id}/delete', name: 'admin_forum_discussion_delete', methods: ['POST'])]
    public function deleteDiscussion(
        ForumDiscussion $discussion,
        Request $request
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $discussion->getId(), $request->request->get('_token'))) {
            try {
                $this->entityManager->remove($discussion);
                $this->entityManager->flush();
                $this->addFlash('success', 'Discussion deleted successfully.');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Unable to delete discussion. Please try again.');
            }
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('admin_forum_discussions');
    }

    // ========== MESSAGES ==========

    #[Route('/messages', name: 'admin_forum_messages')]
    public function messages(ForumMessageRepository $messageRepository): Response
    {
        $messages = $messageRepository->findBy(
            [],
            ['createdAt' => 'DESC'],
            100
        );

        return $this->render('admin/forum/message/index.html.twig', [
            'messages' => $messages,
        ]);
    }

    #[Route('/message/{id}/delete', name: 'admin_forum_message_delete', methods: ['POST'])]
    public function deleteMessage(
        ForumMessage $message,
        Request $request
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $message->getId(), $request->request->get('_token'))) {
            try {
                $this->entityManager->remove($message);
                $this->entityManager->flush();
                $this->addFlash('success', 'Message deleted successfully.');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Unable to delete message. Please try again.');
            }
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('admin_forum_messages');
    }

    // ========== DASHBOARD ==========

    #[Route('/', name: 'admin_forum_dashboard')]
    public function dashboard(
        ForumCategoryRepository $categoryRepository,
        ForumDiscussionRepository $discussionRepository,
        ForumMessageRepository $messageRepository
    ): Response {
        // Basic statistics
        $stats = [
            'categories' => $categoryRepository->count([]),
            'discussions' => $discussionRepository->count([]),
            'messages' => $messageRepository->count([]),
        ];

        // Enhanced statistics
        $recentDiscussions = $discussionRepository->findRecent(5);
        $recentMessages = $messageRepository->findBy(
            [],
            ['createdAt' => 'DESC'],
            5
        );

        // Additional real statistics
        $totalViews = $discussionRepository->createQueryBuilder('d')
            ->select('SUM(d.views)')
            ->getQuery()
            ->getSingleScalarResult() ?? 0;

        $avgMessagesPerDiscussion = $stats['discussions'] > 0 
            ? round($stats['messages'] / $stats['discussions'], 2) 
            : 0;

        $mostActiveCategory = $categoryRepository->createQueryBuilder('c')
            ->select('c.id, c.name, COUNT(d.id) as discussionCount')
            ->leftJoin('c.discussions', 'd')
            ->groupBy('c.id')
            ->orderBy('discussionCount', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        $discussionsThisMonth = $discussionRepository->createQueryBuilder('d')
            ->where('d.createdAt >= :thisMonth')
            ->setParameter('thisMonth', new \DateTime('first day of this month'))
            ->select('COUNT(d.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $messagesThisMonth = $messageRepository->createQueryBuilder('m')
            ->where('m.createdAt >= :thisMonth')
            ->setParameter('thisMonth', new \DateTime('first day of this month'))
            ->select('COUNT(m.id)')
            ->getQuery()
            ->getSingleScalarResult();

        // Additional enhanced statistics
        $topDiscussions = $discussionRepository->findBy(
            [],
            ['views' => 'DESC'],
            5
        );

        $mostActiveUsers = $messageRepository->createQueryBuilder('m')
            ->select('COUNT(m.id) as messageCount, a.name as authorName')
            ->innerJoin('m.author', 'a')
            ->groupBy('m.author')
            ->orderBy('messageCount', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();

        $categoriesWithStats = $categoryRepository->createQueryBuilder('c')
            ->select('c.id, c.name, COUNT(d.id) as discussionCount, COUNT(m.id) as messageCount')
            ->leftJoin('c.discussions', 'd')
            ->leftJoin('d.messages', 'm')
            ->groupBy('c.id')
            ->orderBy('discussionCount', 'DESC')
            ->getQuery()
            ->getResult();

        $recentActivity = $messageRepository->createQueryBuilder('m')
            ->select('m.content, m.createdAt, d.title as discussionTitle, a.name as authorName')
            ->leftJoin('m.discussion', 'd')
            ->leftJoin('m.author', 'a')
            ->orderBy('m.createdAt', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        return $this->render('admin/forum/dashboard.html.twig', [
            'stats' => $stats,
            'recentDiscussions' => $recentDiscussions,
            'recentMessages' => $recentMessages,
            'totalViews' => $totalViews,
            'avgMessagesPerDiscussion' => $avgMessagesPerDiscussion,
            'mostActiveCategory' => $mostActiveCategory,
            'discussionsThisMonth' => $discussionsThisMonth,
            'messagesThisMonth' => $messagesThisMonth,
            'topDiscussions' => $topDiscussions,
            'mostActiveUsers' => $mostActiveUsers,
            'categoriesWithStats' => $categoriesWithStats,
            'recentActivity' => $recentActivity,
        ]);
    }
}
