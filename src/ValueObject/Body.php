<?php
/**
 *
 */

declare(strict_types=1);

namespace General\ValueObject;

final class Body
{
    private $messages;
    private $sandboxMode;

    public function __construct(array $messages = [], bool $sandboxMode = false)
    {
        $this->messages = $messages;
        $this->sandboxMode = $sandboxMode;
    }

    public function toArray(): array
    {
        $return = [
            'Messages'    => $this->messages,
            'SandboxMode' => $this->sandboxMode
        ];

        return $return;
    }
}
