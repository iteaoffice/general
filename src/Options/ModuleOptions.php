<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2019 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Class ModuleOptions
 * @package General\Options
 */
class ModuleOptions extends AbstractOptions implements ImageOptionsInterface
{
    protected string $thumborServer = 'https://image.itea3.org';
    protected string $thumborSecret = 'mKiWlumnpbX1YWpW6lbm';
    protected string $assets = '../../../styles/itea/img';
    protected string $serverUrl = 'https://itea3.org';

    public function getServerUrl(): string
    {
        return $this->serverUrl;
    }

    public function setServerUrl(string $serverUrl): ModuleOptions
    {
        $this->serverUrl = $serverUrl;
        return $this;
    }

    public function getThumborServer(): string
    {
        return $this->thumborServer;
    }

    public function setThumborServer(string $thumborServer): ModuleOptions
    {
        $this->thumborServer = $thumborServer;
        return $this;
    }

    public function getThumborSecret(): string
    {
        return $this->thumborSecret;
    }

    public function setThumborSecret(string $thumborSecret): ModuleOptions
    {
        $this->thumborSecret = $thumborSecret;
        return $this;
    }

    public function getAssets(): string
    {
        return $this->assets;
    }

    public function setAssets(string $assets): ModuleOptions
    {
        $this->assets = $assets;
        return $this;
    }
}
