<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

defined('TYPO3') || die('Access denied.');

$pluginKey = ExtensionUtility::registerPlugin(
    extensionName: 'pxa_social_feed',
    pluginName: 'Showfeed',
    pluginTitle: 'LLL:EXT:pxa_social_feed/Resources/Private/Language/locallang_be.xlf:mlang_tabs_tab',
    pluginIcon: 'ext-pxasocialfeed-wizard-icon',
    pluginDescription: 'LLL:EXT:pxa_social_feed/Resources/Private/Language/locallang_be.xlf:mlang_labels_tabdescr'
);

// @codingStandardsIgnoreStart
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['pxasocialfeed_showfeed'] = 'pages,recursive,layout,select_key';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['pxasocialfeed_showfeed'] = 'pi_flexform';
// @codingStandardsIgnoreEnd

// Add flexform
ExtensionManagementUtility::addPiFlexFormValue(
    $pluginKey,
    'FILE:EXT:pxa_social_feed/Configuration/FlexForm/SocialFeed.xml',
);
