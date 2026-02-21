<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Removed redirect check to allow login page access even when logged in

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/forgot-password', name: 'app_forgot_password')]
    public function forgotPassword(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $error = null;
        $success = null;

        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            
            // Validate email
            if (!$email) {
                $error = 'Email is required';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Please enter a valid email address';
            } else {
                // Check if user exists
                $user = $userRepository->findOneBy(['email' => $email]);
                
                if (!$user) {
                    // Don't reveal if email exists or not for security
                    $success = 'If an account with this email exists, you will receive password reset instructions.';
                } else {
                    // Generate reset token
                    $resetToken = bin2hex(random_bytes(32));
                    $expiryTime = new \DateTime('+1 hour');
                    
                    // Save token to user
                    $user->setResetToken($resetToken);
                    $user->setResetTokenExpiry($expiryTime);
                    
                    // Save to database
                    $entityManager->flush();
                    
                    // Auto-redirect to reset password page
                    $resetLink = $this->generateUrl('app_reset_password', ['token' => $resetToken]);
                    
                    // In production, you would send this link via email
                    // For now, we'll redirect directly
                    $this->addFlash('success', 'Password reset link generated! In production, this would be emailed. Demo link: ' . $resetLink);
                    return $this->redirect($resetLink);
                }
            }
        }

        return $this->render('security/forgot_password.html.twig', [
            'error' => $error,
            'success' => $success,
        ]);
    }

    #[Route('/reset-password/{token}', name: 'app_reset_password')]
    public function resetPassword(Request $request, string $token, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $error = null;
        $success = null;
        
        // Find user by reset token
        $user = $userRepository->findOneBy(['resetToken' => $token]);
        
        if (!$user) {
            $error = 'Invalid or expired reset token';
        } elseif ($user->getResetTokenExpiry() && $user->getResetTokenExpiry() < new \DateTime()) {
            $error = 'Reset token has expired';
        } elseif ($request->isMethod('POST')) {
            $password = $request->request->get('password');
            $confirmPassword = $request->request->get('confirm_password');
            
            // Validate passwords
            if (!$password) {
                $error = 'Password is required';
            } elseif (strlen($password) < 6) {
                $error = 'Password must be at least 6 characters';
            } elseif ($password !== $confirmPassword) {
                $error = 'Passwords do not match';
            } else {
                // Update password
                $user->setPassword($passwordHasher->hashPassword($user, $password));
                $user->setResetToken(null);
                $user->setResetTokenExpiry(null);
                
                $entityManager->flush();
                
                $success = 'Password has been reset successfully! You can now login with your new password.';
            }
        }

        return $this->render('security/reset_password.html.twig', [
            'error' => $error,
            'success' => $success,
            'token' => $token
        ]);
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        // Removed redirect check to allow registration even when logged in

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            
            $user->setRoles(['ROLE_STUDENT']);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Compte créé avec succès ! Vous pouvez vous connecter.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/app/emailbox', name: 'app_emailbox')]
    public function emailbox(): Response
    {
        return $this->render('security/emailbox.html.twig');
    }

    #[Route('/app/emailread', name: 'app_emailread')]
    public function emailread(): Response
    {
        return $this->render('security/emailread.html.twig');
    }

    #[Route('/app/chat_box', name: 'app_chat_box')]
    public function chatBox(): Response
    {
        return $this->render('security/chat_box.html.twig');
    }
}