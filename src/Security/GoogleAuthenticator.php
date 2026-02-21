<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class GoogleAuthenticator extends OAuth2Authenticator
{
    private $clientRegistry;
    private $entityManager;
    private $passwordHasher;
    private $mailer;

    public function __construct(
        ClientRegistry $clientRegistry,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        MailerInterface $mailer
    ) {
        $this->clientRegistry = $clientRegistry;
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        $this->mailer = $mailer;
    }

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'connect_google_check';
    }

    public function authenticate(Request $request): SelfValidatingPassport
    {
        $client = $this->clientRegistry->getClient('google');
        $accessToken = $this->fetchAccessToken($client);

        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function() use ($accessToken, $client) {
                $googleUser = $client->fetchUserFromToken($accessToken);

                $email = $googleUser->getEmail();
                $googleId = $googleUser->getId();
                $name = $googleUser->getName();

                $user = $this->entityManager->getRepository(User::class)->findOneBy(['googleId' => $googleId]);

                if (!$user) {
                    $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
                    
                    if (!$user) {
                        $user = new User();
                        $user->setEmail($email);
                        $user->setName($name);
                        $user->setRoles(['ROLE_STUDENT']);
                        $user->setStatus('pending');
                        $user->setGoogleId($googleId);
                        
                        // Générer un mot de passe aléatoire
                        $randomPlainPassword = bin2hex(random_bytes(16));
                        $hashedPassword = $this->passwordHasher->hashPassword($user, $randomPlainPassword);
                        $user->setPassword($hashedPassword);
                        
                        $this->entityManager->persist($user);
                    } else {
                        $user->setGoogleId($googleId);
                    }
                }

                $this->entityManager->flush();

                // Envoi de l'email avec le mot de passe aléatoire
                if ($user->getStatus() === 'pending' && isset($randomPlainPassword)) {
                    $emailMessage = (new Email())
                        ->from('no-reply@braine.com')
                        ->to($user->getEmail())
                        ->subject('Bienvenue sur Braine ! Vos identifiants Google')
                        ->text("Bonjour " . $user->getName() . ",\n\n" .
                               "Votre compte a été créé avec succès via Google.\n\n" .
                               "Voici votre mot de passe temporaire pour connexion classique :\n" .
                               "Mot de passe : " . $randomPlainPassword . "\n\n" .
                               "Changez-le dès que possible dans votre profil.\n" .
                               "Vous pouvez vous connecter ici : http://127.0.0.1:8000/login\n\n" .
                               "Bienvenue sur Braine !\n" .
                               "L'équipe");
                    
                    $this->mailer->send($emailMessage);
                }

                // Vérifier si le compte est actif
                if ($user->getStatus() !== 'active') {
                    throw new \Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException('Votre compte est en attente d\'approbation par l\'administrateur.');
                }

                return $user;
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new \Symfony\Component\HttpFoundation\RedirectResponse('/dashboard');
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $request->getSession()->getFlashBag()->add('error', 'Authentication failed: ' . $exception->getMessage());
        return new \Symfony\Component\HttpFoundation\RedirectResponse('/login');
    }
}
