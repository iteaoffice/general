<?php

/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 * @category    Invoice
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2019 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        https://github.com/iteaoffice/invoice for the canonical source repository
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
