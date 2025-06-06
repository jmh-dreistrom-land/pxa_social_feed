<?php

declare(strict_types=1);

namespace Pixelant\PxaSocialFeed\Event;

final class BeforeReturnTwitterQueryFieldsEvent
{
    private array $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function setFields(array $fields): void
    {
        $this->fields = $fields;
    }
}
