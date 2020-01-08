<?php

/**
 *
 */

declare(strict_types=1);

namespace General\ValueObject;

final class Header
{
    private string $name;
    private string $value;

    public function __construct(string $name, string $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function toArray(): array
    {
        return [$this->name => $this->value];
    }
}
