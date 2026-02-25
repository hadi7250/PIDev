<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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

    #[Route('/inscription', name: 'app_inscription_front', methods: ['GET', 'POST'])]
    public function inscription(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_front_office');
        }

        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));
            $role = $form->get('role')->getData();
            $user->setRoles($role ? [$role] : []);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Inscription réussie. Vous pouvez vous connecter.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('front/inscription.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/tests', name: 'app_test_index_front')]
    public function tests(): Response
    {
        return $this->render('front/tests.html.twig');
    }
}
