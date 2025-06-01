<?php

declare(strict_types=1);

namespace Pixelant\PxaSocialFeed\Exception;

/**
 * Class FacebookObtainAccessTokenException
 */
class FacebookObtainAccessTokenException extends \Exception
{
    protected int $statusCode = 0;

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }
}
