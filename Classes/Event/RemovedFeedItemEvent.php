<?php

declare(strict_types=1);

namespace Pixelant\PxaSocialFeed\Event;

use Pixelant\PxaSocialFeed\Domain\Model\Feed;

final class RemovedFeedItemEvent
{
    public function __construct(private Feed $feed)
    {
    }

    public function getFeed(): Feed
    {
        return $this->feed;
    }

    public function setFeed(Feed $feed): void
    {
        $this->feed = $feed;
    }
}
