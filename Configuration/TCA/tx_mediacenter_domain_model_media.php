<?php

defined('TYPO3_MODE') or die();

$locallangGeneralPath = 'core/Resources/Private/Language/locallang_general.xlf';
$table = 'tx_mediacenter_domain_model_media';

return [
    'ctrl' => [
        'title' => 'LLL:EXT:mediacenter/Resources/Private/Language/locallang_db.xlf:mediacenter',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'sortby' => 'sorting',
        'languageField' => 'sys_language_uid',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'title',
        'iconfile' => 'EXT:mediacenter/Resources/Public/Icons/Backend/Mediacenter.svg',
    ],
    'interface' => [
        'showRecordFieldList' => '',
    ],
    'types' => [
        '1' => [
            'showitem' => 'title, assets, media_category, media_type, teaser, date, text, downloadable, related_media, slug,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:pages.tabs.access, hidden, --palette--;;1, starttime, endtime',
        ],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $locallangGeneralPath . ':LGL.language',
            'config' => [
                'type' => 'select',
                'readOnly' => false,
                'renderType' => 'selectSingle',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => [
                    ['LLL:EXT:' . $locallangGeneralPath . ':LGL.allLanguages', -1],
                    ['LLL:EXT:' . $locallangGeneralPath . ':LGL.default_value', 0],
                ],
                'default' => 0,
                'fieldWizard' => [
                    'selectIcons' => [
                        'disabled' => false,
                    ],
                ],
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $locallangGeneralPath . ':LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'readOnly' => false,
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => $table,
                'foreign_table_where' => 'AND ' . $table . '.pid=###CURRENT_PID### AND ' . $table . '.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
                'readOnly' => false,
            ],
        ],
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $locallangGeneralPath . ':LGL.hidden',
            'config' => [
                'type' => 'check',
                'readOnly' => false,
            ],
        ],
        'starttime' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $locallangGeneralPath . ':LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'readOnly' => false,
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
                ],
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:' . $locallangGeneralPath . ':LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'readOnly' => false,
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
                ],
            ],
        ],
        'sorting' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mediacenter/Resources/Private/Language/locallang_db.xlf:mediacenter.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'required,trim',
            ],
        ],
        'assets' => [
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/Database.xlf:tt_content.asset_references',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
                'assets',
                [
                    'appearance' => [
                        'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/Database.xlf:tt_content.asset_references.addFileReference',
                    ],
                    // custom configuration for displaying fields in the overlay/reference table
                    // behaves the same as the image field.
                    'overrideChildTca' => [
                        'types' => [
                            '0' => [
                                'showitem' => '
                                --palette--;;imageoverlayPalette,
                                --palette--;;filePalette',
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                                'showitem' => '
                                --palette--;;imageoverlayPalette,
                                --palette--;;filePalette',
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
                                'showitem' => '
                                --palette--;;audioOverlayPalette,
                                --palette--;;filePalette',
                            ],
                            \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
                                'showitem' => '
                                --palette--;;videoOverlayPalette,
                                --palette--;;filePalette',
                            ],
                        ],
                    ],
                ],
                $GLOBALS['TYPO3_CONF_VARS']['SYS']['mediafile_ext']
            ),
        ],
        'teaser' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mediacenter/Resources/Private/Language/locallang_db.xlf:mediacenter.teaser',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'date' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mediacenter/Resources/Private/Language/locallang_db.xlf:mediacenter.date',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'readOnly' => false,
                'size' => 13,
                'eval' => 'date',
                'checkbox' => 0,
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'media_type' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mediacenter/Resources/Private/Language/locallang_db.xlf:mediacenter.media_type',
            'config' => [
                'type' => 'select',
                'readOnly' => false,
                'renderType' => 'selectSingle',
                'default' => \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE,
                'items' => [
                    [
                        'LLL:EXT:mediacenter/Resources/Private/Language/locallang_db.xlf:mediacenter.media_type.' . \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE,
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE,
                    ],
                    [
                        'LLL:EXT:mediacenter/Resources/Private/Language/locallang_db.xlf:mediacenter.media_type.' . \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO,
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO,
                    ],
                    [
                        'LLL:EXT:mediacenter/Resources/Private/Language/locallang_db.xlf:mediacenter.media_type.' . \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO,
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO,
                    ],
                ],
                'fieldWizard' => [
                    'selectIcons' => [
                        'disabled' => false,
                    ],
                ],
            ],
        ],
        'media_category' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mediacenter/Resources/Private/Language/locallang_db.xlf:mediacenter.media_category',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_mediacenter_domain_model_mediacategory',
                'items' => [
                    ['' => 0],
                ],
            ],
        ],
        'text' => [
            'exclude' => true,
            'label' => 'LLL:EXT:mediacenter/Resources/Private/Language/locallang_db.xlf:mediacenter.text',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'eval' => 'trim',
            ],
        ],
        'slug' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:pages.slug',
            'config' => [
                'type' => 'slug',
                'prependSlash' => false,
                'size' => 50,
                'generatorOptions' => [
                    'fields' => ['title'],
                    'prefixParentPageSlug' => false,
                    'replacements' => [
                        '/' => '',
                        '(' => '',
                        ')' => '',
                    ],

                ],
                'fallbackCharacter' => '-',
                'default' => ''
            ]
        ],
        'downloadable' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:mediacenter/Resources/Private/Language/locallang_db.xlf:mediacenter.downloadable',
            'config' => [
                'type' => 'check',
                'readOnly' => false,
            ],
        ],
        'related_media' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:mediacenter/Resources/Private/Language/locallang_db.xlf:mediacenter.related_media',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => $table,
                'foreign_table_where' => ' AND {#' . $table . '}.{#uid} != ###THIS_UID###',
                'MM' => 'tx_mediacenter_media_media_mm',
                'enableMultiSelectFilterTextfield' => true,
            ],
        ],
    ],
];
