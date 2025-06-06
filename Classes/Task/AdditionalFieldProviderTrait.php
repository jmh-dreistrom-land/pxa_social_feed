<?php

declare(strict_types=1);

namespace Pixelant\PxaSocialFeed\Task;

use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageQueue;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Scheduler\Controller\SchedulerModuleController;

trait AdditionalFieldProviderTrait
{
    protected ?FlashMessageQueue $flashMessageQueue;

    /**
     * Get current action
     *
     * @param SchedulerModuleController $schedulerModuleController
     * @return string
     */
    protected function getAction(SchedulerModuleController $schedulerModuleController): string
    {
        return method_exists($schedulerModuleController, 'getCurrentAction')
            ? $schedulerModuleController->getCurrentAction()->value
            : '';
    }

    /**
     * Add a flash message
     */
    protected function addMessage(string $message, ContextualFeedbackSeverity $severity = ContextualFeedbackSeverity::OK): void
    {
        /* @var \TYPO3\CMS\Core\Messaging\FlashMessage $flashMessage */
        $flashMessage = GeneralUtility::makeInstance(
            FlashMessage::class,
            $message,
            '',
            $severity,
            true
        );
        $this->getFlashMessageQueue()->enqueue($flashMessage);
    }

    /**
     * @return FlashMessageQueue
     */
    protected function getFlashMessageQueue(): FlashMessageQueue
    {
        if ($this->flashMessageQueue === null) {
            /** @var FlashMessageService $service */
            $service = GeneralUtility::makeInstance(FlashMessageService::class);
            $this->flashMessageQueue = $service->getMessageQueueByIdentifier();
        }

        return $this->flashMessageQueue;
    }
}
