<?php
/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2019 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 */
declare(strict_types=1);

namespace General\View\Handler;

use Content\Entity\Content;
use Laminas\Authentication\AuthenticationService;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\I18n\Translator\TranslatorInterface;
use Laminas\Mvc\Application;
use Laminas\Router\Http\RouteMatch;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\HeadMeta;
use Laminas\View\Helper\HeadStyle;
use Laminas\View\Helper\HeadTitle;
use Laminas\View\Helper\Placeholder\Container;
use Laminas\View\HelperPluginManager;
use ZfcTwig\View\TwigRenderer;

/**
 * Class AbstractHandler
 *
 * @package Calendar\View
 */
abstract class AbstractHandler extends AbstractHelper
{
    protected HelperPluginManager $helperPluginManager;
    protected RouteMatch $routeMatch;
    protected TwigRenderer $renderer;
    protected Response $response;
    protected Request $request;
    protected AuthenticationService $authenticationService;
    protected TranslatorInterface $translator;

    public function __construct(
        Application $application,
        HelperPluginManager $helperPluginManager,
        TwigRenderer $renderer,
        AuthenticationService $authenticationService,
        TranslatorInterface $translator
    ) {
        $this->helperPluginManager = $helperPluginManager;
        $this->renderer = $renderer;
        $this->authenticationService = $authenticationService;
        $this->translator = $translator;

        //Take the last remaining properties from the application
        $this->routeMatch = $application->getMvcEvent()->getRouteMatch();
        $this->response = $application->getMvcEvent()->getResponse();
        $this->request = $application->getMvcEvent()->getRequest();
    }

    public function extractContentParam(Content $content): array
    {
        $params = [
            'id' => null,
            'docRef' => null,
            'year' => null,
            'page' => 1,
            'limit' => null,
        ];

        foreach ($content->getContentParam() as $contentParam) {
            if (! empty($contentParam->getParameterId())) {
                $params[$contentParam->getParameter()->getParam()] = $contentParam->getParameterId();
            }
        }

        //Overrule all the params, except when we are dealing with docRef
        foreach ($this->routeMatch->getParams() as $routeParam => $value) {
            if ($routeParam !== 'docRef' || null === $params['docRef']) {
                $params[$routeParam] = $value;
            }
        }

        //Convert the ints to ints (it they are null
        null === $params['id'] ?: $params['id'] = (int)$params['id'];
        null === $params['year'] ?: $params['year'] = (int)$params['year'];
        null === $params['page'] ?: $params['page'] = (int)$params['page'];
        null === $params['limit'] ?: $params['limit'] = (int)$params['limit'];

        return $params;
    }

    public function hasDocRef(): bool
    {
        return null !== $this->getDocRef();
    }

    public function getDocRef(): ?string
    {
        return $this->routeMatch->getParam('routeMatch');
    }

    /**
     * @return HeadTitle|Container
     */
    public function getHeadTitle(): HeadTitle
    {
        return $this->helperPluginManager->get('headTitle');
    }

    public function getHeadMeta(): HeadMeta
    {
        return $this->helperPluginManager->get('headMeta');
    }

    public function getHeadStyle(): HeadStyle
    {
        return $this->helperPluginManager->get('headStyle');
    }

    public function translate($string): string
    {
        return $this->translator->translate($string);
    }
}
