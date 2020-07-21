<?php

declare(strict_types=1);

namespace Pluswerk\Mediacenter\Domain\Repository;

use DateTime;
use Pluswerk\Mediacenter\Domain\Model\MediaCategory;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class MediaRepository extends Repository
{
    public const TABLE_NAME = 'tx_mediacenter_domain_model_media';

    public function findByDateAndCategoryAndTerm(
        string $formStartDate = '',
        string $formEndDate = '',
        MediaCategory $category = null,
        string $searchTerm = ''
    ): QueryResultInterface {
        if ($formStartDate === '' && $formEndDate === '' && $category === null && $searchTerm === '') {
            return $this->findAll();
        }

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable(static::TABLE_NAME);
        $queryBuilder
            ->select('*')
            ->from(static::TABLE_NAME);

        if ($formStartDate !== '') {
            $startDate = new DateTime($formStartDate);
            $startDateTimestamp = $startDate->getTimestamp();

            $queryBuilder
                ->andWhere(
                    $queryBuilder->expr()->gte('date', $startDateTimestamp)
                );
        }

        if ($formEndDate !== '') {
            $endDate = new DateTime($formEndDate);
            $endDateTimestamp = $endDate->getTimestamp();

            $queryBuilder
                ->andWhere(
                    $queryBuilder->expr()->lte('date', $endDateTimestamp)
                );
        }

        if ($category !== null && $category instanceof MediaCategory) {
            $queryBuilder
                ->andWhere(
                    $queryBuilder->expr()->eq('media_category', $category->getUid())
                );
        }

        if ($searchTerm !== '') {
            $escapedQuery = $queryBuilder->quote(sprintf('%%%s%%', $searchTerm));
            $queryBuilder
                ->andWhere(
                    $queryBuilder->expr()->orX(
                        $queryBuilder->expr()->like('title', $escapedQuery),
                        $queryBuilder->expr()->like('teaser', $escapedQuery),
                        $queryBuilder->expr()->like('text', $escapedQuery),
                    )
                );
        }

        return $this->doctrineExecute($queryBuilder);
    }

    protected function doctrineExecute(QueryBuilder $queryBuilder): QueryResultInterface
    {
        /** @var \TYPO3\CMS\Extbase\Persistence\Generic\Query $query */
        $query = $this->createQuery();
        $query->statement($queryBuilder);
        return $query->execute();
    }
}
