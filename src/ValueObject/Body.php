<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

/**
 *
 */

declare(strict_types=1);

namespace General\ValueObject;

final class Body
{
    private array $messages;
    private bool $sandboxMode;

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
