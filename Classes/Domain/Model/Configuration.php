<?php

declare(strict_types=1);

namespace Pixelant\PxaSocialFeed\Domain\Model;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Configuration extends AbstractEntity
{
    protected bool $hidden = false;

    protected bool $performCleanUp = true;

    protected string $name = '';

    protected string $imageSize = 'normal_images';

    protected string $socialId = '';

    protected string $endPointEntry = '';

    protected int $maxItems = 0;

    protected int $storage = 0;

    /**
     * @var Token
     */
    #[Lazy]
    protected LazyLoadingProxy|Token|null $token = null;

    /**
     * @var ObjectStorage<BackendUserGroup>
     */
    #[Lazy]
    protected ObjectStorage $beGroup;

    /**
     * Initialize
     */
    public function __construct()
    {
        $this->initializeObject();
    }

    public function initializeObject(): void
    {
        $this->beGroup = new ObjectStorage();
    }

    public function isHidden(): bool
    {
        return $this->hidden;
    }

    public function setHidden(bool $hidden): void
    {
        $this->hidden = $hidden;
    }

    /**
     * @return ObjectStorage<BackendUserGroup>
     */
    public function getBeGroup(): ObjectStorage
    {
        return $this->beGroup;
    }

    /**
     * @param ObjectStorage<BackendUserGroup> $beGroup
     */
    public function setBeGroup(ObjectStorage $beGroup): void
    {
        $this->beGroup = $beGroup;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getImageSize(): string
    {
        return $this->imageSize;
    }

    public function getSocialId(): string
    {
        return $this->socialId;
    }

    public function setSocialId(string $socialId): void
    {
        $this->socialId = $socialId;
    }

    public function getEndPointEntry(): string
    {
        return $this->endPointEntry;
    }

    public function setEndPointEntry(string $endPointEntry): void
    {
        $this->endPointEntry = $endPointEntry;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setImageSize(string $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getMaxItems(): int
    {
        return $this->maxItems;
    }

    public function setMaxItems(?int $maxItems): void
    {
        $this->maxItems = $maxItems ?? 0;
    }

    public function getStorage(): int
    {
        return $this->storage;
    }

    public function setStorage(?int $storage): void
    {
        $this->storage = $storage ?? 0;
    }

    public function getToken(): ?Token
    {
        if ($this->token instanceof LazyLoadingProxy) {
            $this->token->_loadRealInstance();
        }
        return $this->token;
    }

    public function setToken(Token $token): void
    {
        $this->token = $token;
    }

    public function getStorageTitle(): string
    {
        $raw = BackendUtility::getRecord(
            'pages',
            $this->storage,
            'title'
        );

        return is_array($raw) ? $raw['title'] : '';
    }

    public function setPerformCleanUp(bool $performCleanUp): void
    {
        $this->performCleanUp = $performCleanUp;
    }

    public function getPerformCleanUp(): bool
    {
        return $this->performCleanUp;
    }
}
