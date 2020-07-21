<?php

declare(strict_types=1);

namespace Pluswerk\Mediacenter\Controller;

use DateTime;
use Pluswerk\Mediacenter\Domain\Model\Media;
use Pluswerk\Mediacenter\Domain\Model\MediaCategory;
use Pluswerk\Mediacenter\Exception\MediaDownloadDeniedException;
use TYPO3\CMS\Core\Resource\Rendering\RendererRegistry;
use TYPO3\CMS\Core\Resource\Rendering\VimeoRenderer;
use TYPO3\CMS\Core\Resource\Rendering\YouTubeRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\StringUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class MediacenterController extends ActionController
{
    /**
     * @var \Pluswerk\Mediacenter\Domain\Repository\MediaRepository
     * @\TYPO3\CMS\Extbase\Annotation\Inject()
     */
    protected $mediaRepository;

    /**
     * @var \Pluswerk\Mediacenter\Domain\Repository\MediaCategoryRepository
     * @\TYPO3\CMS\Extbase\Annotation\Inject()
     */
    protected $mediaCategoryRepository;

    /**
     * @return void
     */
    public function listAction(): void
    {
        $allMediaRecords = $this->mediaRepository->findAll();

        $mediaTypes = [];
        /** @var Media $mediaRecord */
        foreach ($allMediaRecords as $mediaRecord) {
            if (!isset($mediaTypes[$mediaRecord->getMediaType()])) {
                $mediaTypes[$mediaRecord->getMediaType()] = 1;
            } else {
                $mediaTypes[$mediaRecord->getMediaType()] += 1;
            }
        }

        $this->view->assign('medias', $allMediaRecords);
        $this->view->assign('allMediaCount', count($allMediaRecords));
        $this->view->assign('mediaCategories', $this->mediaCategoryRepository->findAll());
        $this->view->assign('mediaTypes', $mediaTypes);
    }

    /**
     * @param int $mediaType
     * @param string $formStartDate
     * @param string $formEndDate
     * @param \Pluswerk\Mediacenter\Domain\Model\MediaCategory|null $category
     * @param string $query
     * @return void
     */
    public function filterListAction(
        int $mediaType = 0,
        string $formStartDate = '',
        string $formEndDate = '',
        MediaCategory $category = null,
        string $query = ''
    ): void {
        $filteredMediaRecords = [];
        $allMediaRecords = $this->mediaRepository->findByDateAndCategoryAndTerm($formStartDate, $formEndDate, $category, $query);

        $mediaTypes = [];
        /** @var Media $mediaRecord */
        foreach ($allMediaRecords as $mediaRecord) {
            if (!isset($mediaTypes[$mediaRecord->getMediaType()])) {
                $mediaTypes[$mediaRecord->getMediaType()] = 1;
            } else {
                $mediaTypes[$mediaRecord->getMediaType()] += 1;
            }

            if ($mediaType === 0 || $mediaRecord->getMediaType() === $mediaType) {
                $filteredMediaRecords[] = $mediaRecord;
            }
        }

        $this->view->assign('query', $query);
        $this->view->assign('formStartDate', $formStartDate);
        $this->view->assign('formEndDate', $formEndDate);
        $this->view->assign('category', $category);
        $this->view->assign('medias', $filteredMediaRecords);
        $this->view->assign('allMediaCount', count($allMediaRecords));
        $this->view->assign('filteredMediaCount', count($filteredMediaRecords));
        $this->view->assign('mediaCategories', $this->mediaCategoryRepository->findAll());
        $this->view->assign('mediaTypes', $mediaTypes);
        $this->view->assign('activeMediaType', $mediaType);
    }


    /**
     * @param Media $media
     * @return void
     */
    public function showAction(Media $media): void
    {
        $this->view->assign('media', $media);
        $this->view->assign('pageUid', $GLOBALS['TSFE']->id);
    }

    /**
     * @param Media $media
     * @return void
     */
    public function downloadAction(Media $media): void
    {
        if (!$media->isDownloadable()) {
            throw new MediaDownloadDeniedException('The files are not downloadable.');
        }

        $resources = [];
        $fullHash = [];
        /** @var FileReference $asset */
        foreach ($media->getAssets() as $asset) {
            $hash = $asset->getOriginalResource()->getHashedIdentifier();
            $names = $this->getFileAndBaseName($asset->getOriginalResource());
            if (empty($names)) {
                continue;
            }
            $resources[$hash] = $names;
            $splitString = explode('.', $resources[$hash][1]);
            array_push($resources[$hash], $splitString[0] . '.' . end($splitString));
            $fullHash[] = $hash;
        }
        sort($fullHash);
        $fullHash = sha1(implode(',', $fullHash));

        if (empty($resources)) {
            throw new MediaDownloadDeniedException('No downloadable files are given (or only files from video platforms).');
        }

        $filename = $this->createZip($fullHash, $resources);

        header("Content-Type: application/zip");
        header("Content-Disposition: attachment; filename=" . basename($filename));
        header("Content-Length: " . filesize($filename));

        readfile($filename);
        unlink($filename);

        $dir = PATH_site . 'typo3temp/Mediacenter/' . $fullHash . '/';
        if (is_dir($dir)) {
            rmdir($dir);
        }
    }

    /**
     * @param string $fullHash
     * @param string[] $resources
     *
     * @return string
     * @throws \BadFunctionCallException
     * @throws \InvalidArgumentException
     */
    private function createZip(string $fullHash, array $resources): string
    {

        if (!@is_dir(PATH_site . 'typo3temp/Mediacenter/')) {
            GeneralUtility::mkdir(PATH_site . 'typo3temp/Mediacenter/');
        }
        GeneralUtility::mkdir(PATH_site . 'typo3temp/Mediacenter/' . $fullHash . '/');
        $fileName = GeneralUtility::getFileAbsFileName(PATH_site . 'typo3temp/Mediacenter/' . $fullHash . '/MediacenterDownload.zip');

        $zip = new \ZipArchive();
        $zip->open($fileName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        foreach ($resources as [$file, $filename, $filenameWithType]) {
            if (StringUtility::beginsWith($file, 'http://') || StringUtility::beginsWith($file, 'https://')) {
                $download_file = file_get_contents($file);

                $zip->addFromString($filename, $download_file);
            } else {
                if (is_dir($file)) {
                    $zip->addEmptyDir($file);
                } else {
                    $zip->addFile($file, $filenameWithType);
                }
            }
        }
        $zip->close();
        return $fileName;
    }

    /**
     * @param \TYPO3\CMS\Core\Resource\FileReference $fileReference
     * @return string[]
     */
    private function getFileAndBaseName(\TYPO3\CMS\Core\Resource\FileReference $fileReference): array
    {
        $fileRenderer = RendererRegistry::getInstance()->getRenderer($fileReference);

        /** We can't provide videos from YouTube or Vimeo as video file. */
        if ($fileRenderer instanceof YouTubeRenderer || $fileRenderer instanceof VimeoRenderer) {
            return [];
        }

        $filename = $fileReference->getOriginalFile()->getForLocalProcessing(false);
        return [$filename, basename($filename)];
    }
}
