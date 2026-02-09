<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    #[Route('/', name: 'app_front_index')]
    public function index(): Response
    {
        return $this->render('front/index.html.twig');
    }

    #[Route('/about', name: 'app_front_about')]
    public function about(): Response
    {
        return $this->render('front/about.html.twig');
    }

    #[Route('/faq', name: 'app_front_faq')]
    public function faq(): Response
    {
        return $this->render('front/faq.html.twig');
    }

    #[Route('/pricing', name: 'app_front_pricing')]
    public function pricing(): Response
    {
        return $this->render('front/pricing.html.twig');
    }

    #[Route('/testimonial', name: 'app_front_testimonial')]
    public function testimonial(): Response
    {
        return $this->render('front/testimonial.html.twig');
    }

    #[Route('/team-detail', name: 'app_front_team_detail')]
    public function teamDetail(): Response
    {
        return $this->render('front/team-detail.html.twig');
    }

    #[Route('/events', name: 'app_front_events')]
    public function events(): Response
    {
        return $this->render('front/events.html.twig');
    }

    #[Route('/courses', name: 'app_front_courses')]
    public function courses(): Response
    {
        return $this->render('front/course.html.twig');
    }

    #[Route('/feed', name: 'app_front_feed')]
    public function feed(): Response
    {
        return $this->render('front/feed.html.twig');
    }

    #[Route('/quiz', name: 'app_front_quiz')]
    public function quiz(): Response
    {
        return $this->render('front/quiz.html.twig');
    }

    #[Route('/contact', name: 'app_front_contact')]
    public function contact(): Response
    {
        return $this->render('front/contact.html.twig');
    }

    #[Route('/report', name: 'app_front_report')]
    public function report(): Response
    {
        return $this->render('front/report.html.twig');
    }

    #[Route('/services', name: 'app_front_services')]
    public function services(): Response
    {
        return $this->render('front/services.html.twig');
    }
}