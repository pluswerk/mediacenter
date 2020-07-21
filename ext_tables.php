<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function () {
        // add page tsconfig
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            '<INCLUDE_TYPOSCRIPT: source="FILE:EXT:mediacenter/Configuration/TsConfig/Page/Default.tsconfig">'
        );
    }
);
