<?php

declare(strict_types=1);

namespace Pluswerk\Mediacenter\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class MediaCategory extends AbstractEntity
{
    /**
     * @var string
     */
    protected $title = '';

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
}
