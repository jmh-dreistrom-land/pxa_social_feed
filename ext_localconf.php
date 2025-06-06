<?php

defined('TYPO3') or die();

use Pixelant\PxaSocialFeed\Controller\FeedsController;
use Pixelant\PxaSocialFeed\Controller\EidController;
use Pixelant\PxaSocialFeed\Task\ImportTask;
use Pixelant\PxaSocialFeed\Task\ImportTaskAdditionalFieldProvider;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

(function () {
    ExtensionUtility::configurePlugin(
        'PxaSocialFeed',
        'Showfeed',
        [
            FeedsController::class => 'list, listAjax',
        ],
        // non-cacheable actions
        [
            FeedsController::class => 'list',
        ]
    );

    ExtensionUtility::configurePlugin(
        'PxaSocialFeed',
        'LoadFeedAjax',
        [
            FeedsController::class => 'loadFeedAjax',
        ],
        // non-cacheable actions
        [
            FeedsController::class => 'loadFeedAjax',
        ]
    );

    $ll = 'LLL:EXT:' . 'pxa_social_feed' . '/Resources/Private/Language/locallang_be.xlf:';

    // @codingStandardsIgnoreStart
    // Import task
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks'][ImportTask::class] = [
        'extension'        => 'pxa_social_feed',
        'title' => $ll . 'task.import.name',
        'description' => $ll . 'task.import.description',
        'additionalFields' => ImportTaskAdditionalFieldProvider::class,
    ];

    // Register eID to obtain access token
    $eID = EidController::IDENTIFIER;
    $GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include'][$eID] = EidController::class . '::addFbAccessTokenAction';
})();
