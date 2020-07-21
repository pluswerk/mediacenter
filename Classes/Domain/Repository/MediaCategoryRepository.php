<?php

declare(strict_types=1);

namespace Pluswerk\Mediacenter\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;

class MediaCategoryRepository extends Repository
{
    public const TABLE_NAME = 'tx_mediacenter_domain_model_mediacategory';
}
