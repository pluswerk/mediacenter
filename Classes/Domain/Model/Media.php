<?php

declare(strict_types=1);

namespace Pluswerk\Mediacenter\Domain\Model;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Media extends AbstractEntity
{
    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var string
     */
    protected $text = '';

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected $assets;

    /**
     * @var \Pluswerk\Mediacenter\Domain\Model\MediaCategory
     */
    protected $mediaCategory;

    /**
     * @var int
     */
    protected $mediaType = 0;

    /**
     * @var string
     */
    protected $teaser = '';

    /**
     * @var int
     */
    protected $date = 0;

    /**
     * @var bool
     */
    protected $downloadable = false;

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Pluswerk\Mediacenter\Domain\Model\Media>
     */
    protected $relatedMedia;

    public function __construct()
    {
        $this->assets = GeneralUtility::makeInstance(ObjectStorage::class);
        $this->relatedMedia = GeneralUtility::makeInstance(ObjectStorage::class);
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return ObjectStorage
     */
    public function getAssets(): ?ObjectStorage
    {
        return $this->assets;
    }

    /**
     * @return \Pluswerk\Mediacenter\Domain\Model\MediaCategory|null
     */
    public function getMediaCategory(): ?MediaCategory
    {
        return $this->mediaCategory;
    }

    /**
     * @return int
     */
    public function getMediaType(): int
    {
        return $this->mediaType;
    }

    /**
     * @return string
     */
    public function getTeaser(): string
    {
        return $this->teaser;
    }

    /**
     * @return int
     */
    public function getDate(): int
    {
        return $this->date;
    }

    /**
     * @return bool
     */
    public function isDownloadable(): bool
    {
        return $this->downloadable;
    }

    /**
     * @return ObjectStorage
     */
    public function getRelatedMedia(): ?ObjectStorage
    {
        return $this->relatedMedia;
    }
}
