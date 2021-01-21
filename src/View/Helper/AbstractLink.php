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

use Application\Service\AssertionService;
use BjyAuthorize\Service\Authorize;
use General\Options\ModuleOptions;
use General\ValueObject\Link\Link;
use Laminas\I18n\Translator\TranslatorInterface;
use Laminas\Router\RouteStackInterface;

abstract class AbstractLink
{
    protected TranslatorInterface $translator;
    protected ModuleOptions $generalModuleOptions;
    private AssertionService $assertionService;
    private Authorize $authorizeService;
    private RouteStackInterface $router;

    public function __construct(
        AssertionService $assertionService,
        Authorize $authorizeService,
        RouteStackInterface $router,
        TranslatorInterface $translator,
        ModuleOptions $generalModuleOptions
    ) {
        $this->assertionService     = $assertionService;
        $this->authorizeService     = $authorizeService;
        $this->router               = $router;
        $this->translator           = $translator;
        $this->generalModuleOptions = $generalModuleOptions;
    }

    protected function parse(?Link $link): string
    {
        return ($link === null) ? '' : $link->parse($this->router, $this->generalModuleOptions->getServerUrl());
    }

    protected function hasAccess(/*AbstractEntity*/ $entity, string $assertionName, string $action): bool
    {
        $this->assertionService->addResource($entity, $assertionName);
        return $this->authorizeService->isAllowed($entity, $action);
    }
}
