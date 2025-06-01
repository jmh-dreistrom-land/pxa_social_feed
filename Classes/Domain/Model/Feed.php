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

use Pixelant\PxaSocialFeed\Domain\Model\FileReference as PixelantFileReference;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Feeds
 */
class Feed extends AbstractEntity
{
    /**
     * image media type
     */
    public const IMAGE = 1;

    /**
     * video media type
     */
    public const VIDEO = 2;

    protected ?\DateTime $updateDate = null;

    protected string $externalIdentifier = '';

    protected ?\DateTime $postDate = null;

    protected string $postUrl = '';

    protected string $message = '';

    /**
     * @deprecated will be removed in a future version
     */
    protected string $image = '';

    /**
     * @deprecated will be removed in a future version
     */
    protected string $smallImage = '';

    protected int $likes = 0;

    protected string $title = '';

    protected int $type = 0;

    #[Lazy]
    protected LazyLoadingProxy|Configuration|null $configuration = null;

    /**
     * @var ObjectStorage<PixelantFileReference>
     */
    #[Lazy]
    protected ObjectStorage $falMedia;

    protected int $mediaType = self::IMAGE;

    public function __construct()
    {
        // Do not remove the next line: It would break the functionality
        $this->initializeObject();
    }

    /**
     * Initializes all ObjectStorage properties when model is reconstructed from DB (where __construct is not called)
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead.
     */
    public function initializeObject(): void
    {
        $this->falMedia = $this->falMedia ?? new ObjectStorage();
    }

    public function getPostDate(): ?\DateTime
    {
        return $this->postDate;
    }

    public function setPostDate(\DateTime $postDate): void
    {
        $this->postDate = $postDate;
    }

    public function getPostUrl(): string
    {
        return $this->postUrl;
    }

    public function setPostUrl(string $postUrl): void
    {
        $this->postUrl = $postUrl;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getDecodedMessage(): string
    {
        return json_decode(
            sprintf(
                '"%s"',
                $this->message
            )
        );
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @deprecated will be removed in a future version
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @deprecated will be removed in a future version
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * @deprecated will be removed in a future version
     */
    public function getSmallImage(): string
    {
        return $this->smallImage;
    }

    /**
     * @deprecated will be removed in a future version
     */
    public function setSmallImage(string $smallImage): void
    {
        $this->smallImage = $smallImage;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getConfiguration(): ?Configuration
    {
        if ($this->configuration instanceof LazyLoadingProxy) {
            $this->configuration->_loadRealInstance();
        }

        return $this->configuration;
    }

    public function setConfiguration(?Configuration $configuration): void
    {
        $this->configuration = $configuration;
    }

    public function getExternalIdentifier(): string
    {
        return $this->externalIdentifier;
    }

    public function setExternalIdentifier(string $externalIdentifier): void
    {
        $this->externalIdentifier = $externalIdentifier;
    }

    public function getUpdateDate(): ?\DateTime
    {
        return $this->updateDate;
    }

    public function setUpdateDate(\DateTime $updateDate): void
    {
        $this->updateDate = $updateDate;
    }

    public function getLikes(): int
    {
        return $this->likes;
    }

    public function setLikes(int $likes): void
    {
        $this->likes = $likes;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): void
    {
        $this->type = $type;
    }

    public function getMediaType(): int
    {
        return $this->mediaType;
    }

    public function setMediaType(int $mediaType): void
    {
        $this->mediaType = $mediaType;
    }

    /**
     * @return ObjectStorage<PixelantFileReference>|null
     */
    public function getFalMedia(): ?ObjectStorage
    {
        if ($this->falMedia instanceof LazyLoadingProxy) {
            $this->falMedia->_loadRealInstance();
        }
        if ($this->falMedia instanceof ObjectStorage) {
            return $this->falMedia;
        }

        /** @var ObjectStorage<FileReference> */
        $falMedia = new ObjectStorage();

        return $this->falMedia = $falMedia;
    }

    /**
     * @param ObjectStorage<PixelantFileReference> $falMedia
     */
    public function setFalMedia(ObjectStorage $falMedia): void
    {
        $this->falMedia = $falMedia;
    }

    /**
     * Add a Fal media file reference
     *
     * @param FileReference $falMedia
     */
    public function addFalMedia(FileReference $falMedia): void
    {
        $this->falMedia = $this->getFalMedia();
        $this->falMedia->attach($falMedia);
    }
}
