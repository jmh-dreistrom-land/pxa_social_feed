<?php

declare(strict_types=1);

namespace Pixelant\PxaSocialFeed\Event;

use Pixelant\PxaSocialFeed\Domain\Model\Configuration;
use Pixelant\PxaSocialFeed\Domain\Model\Feed;

final class BeforeUpdateYoutubeFeedEvent
{
     function __construct(
        private Feed $feedItem,
        private readonly array $rawData,
        private readonly Configuration $configuration)
    {
    }

    public function getFeedItem(): Feed
    {
        return $this->feedItem;
    }

    public function getRawData(): array
    {
        return $this->rawData;
    }

    public function getConfiguration(): Configuration
    {
        return $this->configuration;
    }

    public function setFeedItem(Feed $feedItem): void
    {
        $this->feedItem = $feedItem;
    }
}
