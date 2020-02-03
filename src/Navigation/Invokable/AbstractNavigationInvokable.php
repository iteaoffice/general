<?php

/**
 * ITEA Office all rights reserved
 *
 * @category  Publication
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2019 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Navigation\Invokable;

use Doctrine\Common\Collections\ArrayCollection;
use General\Navigation\Service\NavigationService;
use Laminas\I18n\Translator\TranslatorInterface;
use Laminas\Navigation\Page\Mvc;

/**
 * Class AbstractNavigationInvokable
 * @package General\Navigation\Invokable
 */
abstract class AbstractNavigationInvokable implements NavigationInvokableInterface
{
    protected NavigationService   $navigationService;
    protected TranslatorInterface $translator;

    public function __construct(
        NavigationService $navigationService,
        TranslatorInterface $translator
    )
    {
        $this->navigationService = $navigationService;
        $this->translator        = $translator;
    }

    abstract public function __invoke(Mvc $page): void;

    protected function getEntities(): ArrayCollection
    {
        return $this->navigationService->getEntities();
    }

    protected function translate($string): string
    {
        return $this->translator->translate($string);
    }
}
