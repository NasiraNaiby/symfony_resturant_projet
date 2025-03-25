<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Psr\Log\LoggerInterface;

class MailerService {
    private $mailer;
    private $logger;

    public function __construct(MailerInterface $mailer, LoggerInterface $logger) {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    public function sendEmail(string $to, string $content, string $subject): void {
        $email = (new Email())
            ->from('naeibinazari@gmail.com') 
            ->to($to)
            ->subject($subject)
            ->html($content);

        $this->logger->info('Sending email', ['to' => $to, 'subject' => $subject]);

        $this->mailer->send($email);
    }
}
