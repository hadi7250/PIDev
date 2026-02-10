<?php
namespace App\Controller;
use App\Repository\QuizRepository; // Uses your Quiz entity
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(QuizRepository $quizRepository): Response
    {
        // This is the CRITICAL part. 
        // We find all Quizzes and pass them to the template as the variable 'quizzes'.
        return $this->render('front/index.html.twig', [
            'quizzes' => $quizRepository->findAll(),
        ]);
    }
}