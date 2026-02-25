<?php

namespace App\Service;

use App\Entity\Event;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Envoi d'emails via YouremailAPI (https://youremailapi.com).
 * Nécessite un compte SMTP et un template créés sur https://youremailapi.com/admin
 *
 * @see https://docs.youremailapi.com/docs/send-email
 */
final class YouremailApiMailer
{
    private const API_URL = 'https://api.youremailapi.com/mailer/';

    public function __construct(
        private HttpClientInterface $httpClient,
        private string $youremailApiKey,
        private string $youremailSmtpAccount,
        private string $youremailTemplate,
        private string $youremailTo,
        private ?LoggerInterface $logger = null,
    ) {
    }

    /**
     * Envoie un email via YouremailAPI (template + variables).
     */
    public function send(string $to, string $subject, string $smtpAccount, string $template, array $variables = []): bool
    {
        if ($this->youremailApiKey === '' || $smtpAccount === '' || $template === '') {
            $this->logger?->warning('YouremailAPI: email non envoyé — clé API, smtp_account ou template manquant.');
            return false;
        }

        $payload = [
            'subject' => $subject,
            'to' => $to,
            'smtp_account' => $smtpAccount,
            'template' => $template,
            'variables' => $variables,
        ];

        try {
            $response = $this->httpClient->request('POST', self::API_URL, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'apikey' => $this->youremailApiKey,
                ],
                'json' => $payload,
            ]);
            $status = $response->getStatusCode();
            if ($status >= 200 && $status < 300) {
                $this->logger?->info('YouremailAPI: email envoyé avec succès.', ['status' => $status]);
                return true;
            }
            $body = $response->getContent(false);
            $this->logger?->error('YouremailAPI: erreur API.', ['status' => $status, 'response' => $body]);
            return false;
        } catch (\Throwable $e) {
            $this->logger?->error('YouremailAPI: exception.', ['message' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Envoie la notification "nouvel événement créé" au destinataire configuré (mohamednasrixxx@gmail.com).
     * Le template YouremailAPI doit contenir les variables : %titre%, %categorie%, %lieu%, %date_debut%, %date_fin%, %description%
     */
    public function sendEventCreatedNotification(Event $event): bool
    {
        if ($this->youremailTo === '' || $this->youremailSmtpAccount === '' || $this->youremailTemplate === '') {
            $this->logger?->warning('YouremailAPI: notification non envoyée — YOUREMAIL_TO, YOUREMAIL_SMTP_ACCOUNT ou YOUREMAIL_TEMPLATE manquant dans .env');
            return false;
        }

        $titre = $event->getTitre() ?? 'Sans titre';
        $lieu = $event->getLieu() ?? '-';
        $dateDebut = $event->getDateDebut() ? $event->getDateDebut()->format('d/m/Y H:i') : '-';
        $dateFin = $event->getDateFin() ? $event->getDateFin()->format('d/m/Y H:i') : '-';
        $categorie = $event->getCategory() ? $event->getCategory()->getName() : '-';
        $description = $event->getDescription() ? mb_substr($event->getDescription(), 0, 500) . (mb_strlen($event->getDescription()) > 500 ? '...' : '') : '-';

        $subject = sprintf('Nouvel événement créé : %s', $titre);
        $variables = [
            '%titre%' => $titre,
            '%categorie%' => $categorie,
            '%lieu%' => $lieu,
            '%date_debut%' => $dateDebut,
            '%date_fin%' => $dateFin,
            '%description%' => $description,
        ];

        return $this->send(
            $this->youremailTo,
            $subject,
            $this->youremailSmtpAccount,
            $this->youremailTemplate,
            $variables,
        );
    }
}
