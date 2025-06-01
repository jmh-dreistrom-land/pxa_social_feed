<?php

declare(strict_types=1);

namespace Pixelant\PxaSocialFeed\Domain\Model;

use TYPO3\CMS\Extbase\Domain\Model\FileReference as ExtbaseFileReference;
use TYPO3\CMS\Core\Resource\File;

class FileReference extends ExtbaseFileReference
{
    public function setOriginalFile(File $originalFile): void
    {
        $this->uidLocal = (int)$originalFile->getUid();
    }

    public function getFileUid(): int
    {
        return $this->uidLocal;
    }
}
