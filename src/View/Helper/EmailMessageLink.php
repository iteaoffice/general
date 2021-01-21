<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\View\Helper;

use General\Entity\EmailMessage;
use General\ValueObject\Link\Link;

/**
 * Class EmailMessageLink
 * @package General\View\Helper
 */
final class EmailMessageLink extends AbstractLink
{
    public function __invoke(EmailMessage $emailMessage): string
    {
        $routeParams       = [];
        $routeParams['id'] = $emailMessage->getId();

        $linkParams = [
            'icon'  => 'far fa-envelope',
            'route' => 'zfcadmin/email/view',
            'text'  => $emailMessage->getSubject(),
        ];

        $linkParams['action']      = 'view';
        $linkParams['show']        = 'text';
        $linkParams['routeParams'] = $routeParams;

        return $this->parse(Link::fromArray($linkParams));
    }
}
