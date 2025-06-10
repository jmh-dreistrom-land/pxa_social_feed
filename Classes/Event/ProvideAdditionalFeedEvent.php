<?php

declare(strict_types=1);

namespace Pixelant\PxaSocialFeed\Event;

use Pixelant\PxaSocialFeed\Feed\AbstractAdditionalFeed;

class ProvideAdditionalFeedEvent
{
    private array $additionalFeeds = [];

    public function addFeed(AbstractAdditionalFeed $additionalFeed): void
    {
        $this->additionalFeeds[$additionalFeed::class] = $additionalFeed;
    }

    /**
     * @internal
     */
    public function getFeeds(): array
    {
        return $this->additionalFeeds;
    }
}
