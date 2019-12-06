<?php

declare(strict_types=1);

namespace General\View\Helper;

use Application\Service\AssertionService;
use BjyAuthorize\Service\Authorize;
use General\ValueObject\Link\Link;
use Zend\I18n\Translator\TranslatorInterface;
use Zend\Router\RouteStackInterface;

abstract class AbstractLink
{
    private   AssertionService    $assertionService;
    private   Authorize           $authorizeService;
    private   RouteStackInterface $router;
    protected TranslatorInterface $translator;
    private   string              $serverUrl;

    public function __construct(
        AssertionService    $assertionService,
        Authorize           $authorizeService,
        RouteStackInterface $router,
        TranslatorInterface $translator,
        array               $config = []
    )
    {
        $this->assertionService = $assertionService;
        $this->authorizeService = $authorizeService;
        $this->router           = $router;
        $this->translator       = $translator;
        $this->serverUrl        = $config['deeplink']['serverUrl'] ?? '';
    }

    protected function parse(?Link $link): string
    {
        return ($link === null) ? '' : $link->parse($this->router, $this->serverUrl);
    }

    protected function hasAccess(/*AbstractEntity*/ $entity, string $assertionName, string $action): bool
    {
        $this->assertionService->addResource($entity, $assertionName);
        return $this->authorizeService->isAllowed($entity, $action);
    }
}