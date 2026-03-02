<?php

namespace App\Controller;

use App\Entity\ForumMessage;
use App\Repository\ForumMessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/forum/vote')]
class VoteController extends AbstractController
{
    private const VOTE_SESSION_KEY = 'forum_votes';

    public function __construct(private RequestStack $requestStack)
    {
    }

    #[Route('/up/{messageId}', name: 'forum_vote_up', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function upvote(
        int $messageId,
        ForumMessageRepository $messageRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        return $this->handleVote($messageId, 'up', $messageRepository, $entityManager, $request);
    }

    #[Route('/down/{messageId}', name: 'forum_vote_down', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function downvote(
        int $messageId,
        ForumMessageRepository $messageRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        return $this->handleVote($messageId, 'down', $messageRepository, $entityManager, $request);
    }

    private function handleVote(
        int $messageId,
        string $voteType,
        ForumMessageRepository $messageRepository,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $message = $messageRepository->find($messageId);
        
        if (!$message) {
            $this->addFlash('error', 'Message not found.');
            return $this->redirectToRoute('forum_discussion', ['id' => $message->getDiscussion()->getId()]);
        }

        // Prevent users from voting on their own messages
        if ($message->getAuthor() === $this->getUser()) {
            $this->addFlash('error', 'You cannot vote on your own messages.');
            return $this->redirectToRoute('forum_discussion', ['id' => $message->getDiscussion()->getId()]);
        }

        // Get existing votes from session
        $votes = $this->requestStack->getSession()->get(self::VOTE_SESSION_KEY, []);
        $voteKey = $messageId;

        // Check if user already voted on this message
        if (isset($votes[$voteKey])) {
            $existingVote = $votes[$voteKey];
            
            // If same vote type, remove the vote (toggle)
            if ($existingVote === $voteType) {
                if ($voteType === 'up') {
                    $message->decrementUpvotes();
                } else {
                    $message->decrementDownvotes();
                }
                unset($votes[$voteKey]);
                $this->addFlash('success', 'Vote removed.');
            } else {
                // Change vote type
                if ($existingVote === 'up') {
                    $message->decrementUpvotes();
                    $message->incrementDownvotes();
                } else {
                    $message->decrementDownvotes();
                    $message->incrementUpvotes();
                }
                $votes[$voteKey] = $voteType;
                $this->addFlash('success', 'Vote changed.');
            }
        } else {
            // New vote
            if ($voteType === 'up') {
                $message->incrementUpvotes();
            } else {
                $message->incrementDownvotes();
            }
            $votes[$voteKey] = $voteType;
            $this->addFlash('success', 'Vote added.');
        }

        // Save votes to session
        $this->requestStack->getSession()->set(self::VOTE_SESSION_KEY, $votes);

        // Persist changes
        $entityManager->flush();

        return $this->redirectToRoute('forum_discussion', ['id' => $message->getDiscussion()->getId()]);
    }
}
