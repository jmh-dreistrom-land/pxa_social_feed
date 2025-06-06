<?php

declare(strict_types=1);

namespace Pixelant\PxaSocialFeed\Service\Notification;

use TYPO3\CMS\Core\Mail\MailMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MailUtility;

class NotificationService
{
    protected string $senderEmail = '';
    protected string $receiverEmail = '';

    public function __construct(string $receiverEmail = '', string $senderEmail = '')
    {
        $this->receiverEmail = $receiverEmail;
        $this->senderEmail = $senderEmail;
    }

    /**
     * Notify by email
     */
    public function notify(string $subject, string $message): void
    {
        $mailer = $this->getMailer();

        $mailer
            ->subject($subject)
            ->html($message)
            ->send();
    }

    public function canSendEmail(): bool
    {
        return GeneralUtility::validEmail($this->senderEmail) && GeneralUtility::validEmail($this->receiverEmail);
    }

    /**
     * Prepare mailer
     */
    protected function getMailer(): MailMessage
    {
        $mail = GeneralUtility::makeInstance(MailMessage::class);

        $mail
            ->from(MailUtility::getSystemFromAddress())
            ->to($this->receiverEmail);

        return $mail;
    }

    public function getSenderEmail(): string
    {
        return $this->senderEmail;
    }

    public function setSenderEmail(string $senderEmail): void
    {
        $this->senderEmail = $senderEmail;
    }

    public function getReceiverEmail(): string
    {
        return $this->receiverEmail;
    }

    public function setReceiverEmail(string $receiverEmail): void
    {
        $this->receiverEmail = $receiverEmail;
    }
}
