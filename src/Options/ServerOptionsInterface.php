<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\Options;

/**
 * Interface ServerOptionsInterface
 * @package Deeplink\Options
 */
interface ServerOptionsInterface
{
    public function getServerUrl(): string;

    public function setServerUrl(string $serverUrl): ModuleOptions;
}
