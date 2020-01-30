<?php

/**
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2019 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        http://github.com/iteaoffice/general for the canonical source repository
 */

declare(strict_types=1);

namespace General\View\Helper;

use General\Entity\Log;
use General\ValueObject\Link\Link;

/**
 * Class LogLink
 * @package General\View\Helper
 */
final class LogLink extends AbstractLink
{
    public function __invoke(Log $log): string
    {
        $routeParams = [];
        $routeParams['id'] = $log->getId();

        $linkParams = [
            'icon' => 'fas fa-link',
            'route' => 'zfcadmin/log/view',
            'text' => $log->getEvent(),
        ];

        $linkParams['action'] = 'view';
        $linkParams['show'] = 'text';
        $linkParams['routeParams'] = $routeParams;

        return $this->parse(Link::fromArray($linkParams));
    }
}
