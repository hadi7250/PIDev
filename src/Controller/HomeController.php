<?php

namespace App\Controller;

use App\Repository\CoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(CoursRepository $coursRepository): Response
    {
        // This fetches all courses from your database and passes them to the Twig file
        return $this->render('home/index.html.twig', [
            'courses' => $coursRepository->findAll(),
        ]);
    }
}