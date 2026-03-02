<?php

namespace App\Controller;

use App\Entity\ForumCategory;
use App\Entity\ForumDiscussion;
use App\Entity\ForumMessage;
use App\Form\ForumDiscussionType;
use App\Form\ForumMessageType;
use App\Form\ReplyMessageType;
use App\Repository\ForumCategoryRepository;
use App\Repository\ForumDiscussionRepository;
use App\Repository\ForumMessageRepository;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/forum')]
class ForumController extends AbstractController
{
    #[Route('/', name: 'forum_index')]
    public function index(
        ForumCategoryRepository $categoryRepository,
        ForumDiscussionRepository $discussionRepository
    ): Response {
        $categories = $categoryRepository->findAllWithDiscussionCount();
        $recentDiscussions = $discussionRepository->findRecent(5);
        
        // Get trending data
        $mostViewedToday = $discussionRepository->findMostViewedToday();
        $mostDiscussedToday = $discussionRepository->findMostDiscussedToday();
        $topViewedOverall = $discussionRepository->findTopViewedOverall(5);

        return $this->render('forum/index.html.twig', [
            'categories' => $categories,
            'recentDiscussions' => $recentDiscussions,
            'mostViewedToday' => $mostViewedToday,
            'mostDiscussedToday' => $mostDiscussedToday,
            'topViewedOverall' => $topViewedOverall,
        ]);
    }

    #[Route('/category/{id}', name: 'forum_category')]
    public function category(
        ForumCategory $category,
        ForumDiscussionRepository $discussionRepository
    ): Response {
        $discussions = $discussionRepository->findByCategory($category->getId());

        return $this->render('forum/discussion_list.html.twig', [
            'category' => $category,
            'discussions' => $discussions,
        ]);
    }

    #[Route('/discussion/{id}', name: 'forum_discussion')]
    public function discussion(
        ForumDiscussion $discussion,
        ForumMessageRepository $messageRepository,
        Request $request,
        EntityManagerInterface $entityManager,
        NotificationService $notificationService
    ): Response {
        // Increment views with session protection
        $sessionKey = 'viewed_discussion_' . $discussion->getId();
        if (!$request->getSession()->get($sessionKey)) {
            $discussion->incrementViews();
            $entityManager->flush();
            $request->getSession()->set($sessionKey, true);
        }

        // Get hierarchical messages
        $rootMessages = $messageRepository->findMessagesHierarchy($discussion->getId());

        // Only logged-in users can add messages
        $message = null;
        $form = null;
        
        if ($this->getUser()) {
            $message = new ForumMessage();
            $message->setAuthor($this->getUser());
            $message->setDiscussion($discussion);
            
            $form = $this->createForm(ForumMessageType::class, $message);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($message);
                $entityManager->flush();

                // Create notification for discussion author
                $notificationService->createReplyToDiscussionNotification($discussion, $message);

                $this->addFlash('success', 'Votre message a été ajouté avec succès.');
                return $this->redirectToRoute('forum_discussion', ['id' => $discussion->getId()]);
            }
        }

        return $this->render('forum/show.html.twig', [
            'discussion' => $discussion,
            'rootMessages' => $rootMessages,
            'messageForm' => $form?->createView(),
        ]);
    }

    #[Route('/new', name: 'forum_new_discussion')]
    #[IsGranted('ROLE_USER')]
    public function newDiscussion(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $discussion = new ForumDiscussion();
        $discussion->setAuthor($this->getUser());

        $form = $this->createForm(ForumDiscussionType::class, $discussion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle file upload
            $attachmentFile = $form->get('attachmentFile')->getData();
            if ($attachmentFile) {
                // Generate a unique filename
                $originalFilename = pathinfo($attachmentFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$attachmentFile->guessExtension();

                // Move the file to the target directory
                try {
                    $attachmentFile->move(
                        $this->getParameter('kernel.project_dir').'/public/uploads/discussions',
                        $newFilename
                    );
                    $discussion->setAttachmentName($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'There was an error uploading your file.');
                }
            }

            $entityManager->persist($discussion);
            $entityManager->flush();

            $this->addFlash('success', 'Votre discussion a été créée avec succès.');
            return $this->redirectToRoute('forum_discussion', ['id' => $discussion->getId()]);
        }

        return $this->render('forum/new_discussion.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/discussion/{id}/edit', name: 'forum_edit_discussion')]
    #[IsGranted('ROLE_USER')]
    public function editDiscussion(
        ForumDiscussion $discussion,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        // Check if user is the author of the discussion
        if ($discussion->getAuthor() !== $this->getUser()) {
            $this->addFlash('error', 'You can only edit your own discussions.');
            return $this->redirectToRoute('forum_discussion', ['id' => $discussion->getId()]);
        }

        $form = $this->createForm(ForumDiscussionType::class, $discussion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle file upload
            $attachmentFile = $form->get('attachmentFile')->getData();
            if ($attachmentFile) {
                // Delete old attachment if exists
                if ($discussion->getAttachmentName()) {
                    $oldFile = $this->getParameter('kernel.project_dir').'/public/uploads/discussions/'.$discussion->getAttachmentName();
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }

                // Generate a unique filename
                $originalFilename = pathinfo($attachmentFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$attachmentFile->guessExtension();

                // Move the file to the target directory
                try {
                    $attachmentFile->move(
                        $this->getParameter('kernel.project_dir').'/public/uploads/discussions',
                        $newFilename
                    );
                    $discussion->setAttachmentName($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'There was an error uploading your file.');
                }
            }

            $discussion->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();

            $this->addFlash('success', 'Your discussion has been updated successfully.');
            return $this->redirectToRoute('forum_discussion', ['id' => $discussion->getId()]);
        }

        return $this->render('forum/edit_discussion.html.twig', [
            'form' => $form->createView(),
            'discussion' => $discussion,
        ]);
    }

    #[Route('/message/{id}/edit', name: 'forum_edit_message')]
    #[IsGranted('ROLE_USER')]
    public function editMessage(
        ForumMessage $message,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        // Check if user is the author of the message
        if ($message->getAuthor() !== $this->getUser()) {
            $this->addFlash('error', 'You can only edit your own messages.');
            return $this->redirectToRoute('forum_discussion', ['id' => $message->getDiscussion()->getId()]);
        }

        $form = $this->createForm(ForumMessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Your message has been updated successfully.');
            return $this->redirectToRoute('forum_discussion', ['id' => $message->getDiscussion()->getId()]);
        }

        return $this->render('forum/edit_message.html.twig', [
            'form' => $form->createView(),
            'message' => $message,
        ]);
    }

    #[Route('/message/add/{discussionId}', name: 'forum_add_message', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function addMessage(
        int $discussionId,
        Request $request,
        ForumDiscussionRepository $discussionRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $content = $request->request->get('content');
        
        if (empty($content)) {
            return new JsonResponse(['error' => 'Le message ne peut pas être vide.'], 400);
        }

        $discussion = $discussionRepository->find($discussionId);
        if (!$discussion) {
            return new JsonResponse(['error' => 'Discussion non trouvée.'], 404);
        }

        $message = new ForumMessage();
        $message->setContent($content);
        $message->setAuthor($this->getUser());
        $message->setDiscussion($discussion);

        $entityManager->persist($message);
        $entityManager->flush();

        return new JsonResponse([
            'success' => true,
            'message' => [
                'id' => $message->getId(),
                'content' => $message->getContent(),
                'author' => $message->getAuthor()->getName(),
                'createdAt' => $message->getCreatedAt()->format('d/m/Y H:i'),
                'upvotes' => $message->getUpvotes(),
                'downvotes' => $message->getDownvotes(),
            ]
        ]);
    }

    #[Route('/search', name: 'forum_search')]
    public function search(Request $request, ForumDiscussionRepository $discussionRepository): Response
    {
        $query = $request->query->get('q');
        
        if (empty($query)) {
            return $this->redirectToRoute('forum_index');
        }

        $discussions = $discussionRepository->search($query);

        return $this->render('forum/search_results.html.twig', [
            'discussions' => $discussions,
            'query' => $query,
        ]);
    }

    #[Route('/discussion/{id}/delete', name: 'forum_delete_discussion', methods: ['POST'])]
    public function deleteDiscussion(
        ForumDiscussion $discussion,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        // Check CSRF token
        if (!$this->isCsrfTokenValid('delete_discussion_' . $discussion->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Invalid CSRF token.');
            return $this->redirectToRoute('forum_discussion', ['id' => $discussion->getId()]);
        }

        // Check authorization: only author or admin can delete
        $user = $this->getUser();
        if (!$user || ($discussion->getAuthor() !== $user && !in_array('ROLE_ADMIN', $user->getRoles()))) {
            $this->addFlash('error', 'You are not authorized to delete this discussion.');
            return $this->redirectToRoute('forum_discussion', ['id' => $discussion->getId()]);
        }

        // Get category for redirect
        $categoryId = $discussion->getCategory()->getId();

        // Delete discussion (cascade will delete messages)
        $entityManager->remove($discussion);
        $entityManager->flush();

        $this->addFlash('success', 'Discussion deleted successfully.');
        return $this->redirectToRoute('forum_category', ['id' => $categoryId]);
    }

    #[Route('/message/{id}/delete', name: 'forum_delete_message', methods: ['POST'])]
    public function deleteMessage(
        ForumMessage $message,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        // Check CSRF token
        if (!$this->isCsrfTokenValid('delete_message_' . $message->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Invalid CSRF token.');
            return $this->redirectToRoute('forum_discussion', ['id' => $message->getDiscussion()->getId()]);
        }

        // Check authorization: only author or admin can delete
        $user = $this->getUser();
        if (!$user || ($message->getAuthor() !== $user && !in_array('ROLE_ADMIN', $user->getRoles()))) {
            $this->addFlash('error', 'You are not authorized to delete this message.');
            return $this->redirectToRoute('forum_discussion', ['id' => $message->getDiscussion()->getId()]);
        }

        // Get discussion for redirect
        $discussionId = $message->getDiscussion()->getId();

        // Delete message
        $entityManager->remove($message);
        $entityManager->flush();

        $this->addFlash('success', 'Message deleted successfully.');
        return $this->redirectToRoute('forum_discussion', ['id' => $discussionId]);
    }

    #[Route('/message/{id}/reply', name: 'forum_reply_message', methods: ['POST'])]
    public function replyToMessage(
        ForumMessage $parentMessage,
        Request $request,
        EntityManagerInterface $entityManager,
        ForumMessageRepository $messageRepository,
        NotificationService $notificationService
    ): Response {
        // Check if user is logged in
        if (!$this->getUser()) {
            $this->addFlash('error', 'You must be logged in to reply.');
            return $this->redirectToRoute('forum_discussion', ['id' => $parentMessage->getDiscussion()->getId()]);
        }

        // Check if message can be replied to (prevent deep nesting)
        if (!$messageRepository->canReplyToMessage($parentMessage->getId())) {
            $this->addFlash('error', 'This message cannot be replied to (maximum nesting depth reached).');
            return $this->redirectToRoute('forum_discussion', ['id' => $parentMessage->getDiscussion()->getId()]);
        }

        // Create reply form
        $reply = new ForumMessage();
        $form = $this->createForm(ReplyMessageType::class, $reply);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Set reply properties
            $reply->setAuthor($this->getUser());
            $reply->setDiscussion($parentMessage->getDiscussion());
            $reply->setParent($parentMessage);

            $entityManager->persist($reply);
            $entityManager->flush();

            // Create notification for parent message author
            $notificationService->createReplyToMessageNotification($parentMessage, $reply);

            $this->addFlash('success', 'Reply added successfully.');
        } else {
            // Handle form errors
            foreach ($form->getErrors(true) as $error) {
                $this->addFlash('error', $error->getMessage());
            }
        }

        return $this->redirectToRoute('forum_discussion', ['id' => $parentMessage->getDiscussion()->getId()]);
    }
}
