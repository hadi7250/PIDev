<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FrontController extends AbstractController
{
    #[Route('/', name: 'app_front_office')]
    public function index(): Response
    {
        return $this->render('front/index.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }

    #[Route('/inscription', name: 'app_inscription_front')]
    public function inscription(): Response
    {
        // For now, render the same template or a placeholder. 
        // Ideally this would show a registration form.
        // Given indexFront.twig seems to be the registration page structure, we might use that if we have a form.
        // For now, let's render a simple placeholder to fix the route error.
        return $this->render('front/inscription.html.twig');
    }

    #[Route('/tests', name: 'app_test_index_front')]
    public function tests(): Response
    {
        return $this->render('front/tests.html.twig');
    }
}
