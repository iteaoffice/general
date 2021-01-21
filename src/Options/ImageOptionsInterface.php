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
 * Interface CalendarOptionsInterface.
 */
interface ImageOptionsInterface
{
    public function getThumborServer(): string;

    public function setThumborServer(string $thumborServer): ModuleOptions;

    public function getThumborSecret(): string;

    public function setThumborSecret(string $thumborSecret): ModuleOptions;

    public function getAssets(): string;

    public function setAssets(string $assets): ModuleOptions;
}
