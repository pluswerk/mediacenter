<?php
defined('TYPO3_MODE') or die('Access denied.');

call_user_func(
    function () {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'Pluswerk.Mediacenter',
            'Mediacenter',
            'LLL:EXT:mediacenter/Resources/Private/Language/locallang_db.xlf:pluginWizard.title'
        );
    }
);
