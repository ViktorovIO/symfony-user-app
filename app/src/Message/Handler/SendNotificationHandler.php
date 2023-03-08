<?php

declare(strict_types=1);

namespace App\Message\Handler;

use App\Exception\SendEmailException;
use App\Message\Notification\SendNotificationMessageInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Email;

class SendNotificationHandler implements MessageHandlerInterface
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @throws SendEmailException
     */
    public function __invoke(SendNotificationMessageInterface $message): void
    {
        $email = (new Email())
            ->from('noreply@symfony-user-app.com')
            ->to($message->getRecipient())
            ->subject('Account registration')
            ->text($message->getMessage());

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $exception) {
            throw new SendEmailException('Mail is not sended. Reason: ' . $exception->getMessage());
        }
    }
}