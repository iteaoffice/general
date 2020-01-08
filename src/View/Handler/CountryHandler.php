<?php

/**
 * ITEA Office all rights reserved
 *
 * @category   General
 *
 * @author     Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright  Copyright (c) 2004-2017 ITEA Office (http://itea3.org)
 */

declare(strict_types=1);

namespace General\View\Handler;

use Contact\Service\ContactService;
use Content\Entity\Content;
use General\Entity\Country;
use General\Search\Service\CountrySearchService;
use General\Service\CountryService;
use General\ValueObject\Link\LinkDecoration;
use General\View\Helper\Country\CountryLink;
use General\View\Helper\Country\CountryMap;
use Program\Service\ProgramService;
use Laminas\Authentication\AuthenticationService;
use Laminas\Http\Response;
use Laminas\I18n\Translator\TranslatorInterface;
use Laminas\Mvc\Application;
use Laminas\View\HelperPluginManager;
use ZfcTwig\View\TwigRenderer;

/**
 * Class ProjectHandler
 *
 * @package Project\View\Handler
 */
final class CountryHandler extends AbstractHandler
{
    private CountryService $countryService;
    private CountrySearchService $countrySearchService;
    private ContactService $contactService;
    private ProgramService $programService;

    public function __construct(
        Application $application,
        HelperPluginManager $helperPluginManager,
        TwigRenderer $renderer,
        AuthenticationService $authenticationService,
        TranslatorInterface $translator,
        CountryService $countryService,
        CountrySearchService $countrySearchService,
        ContactService $contactService,
        ProgramService $programService
    ) {
        parent::__construct(
            $application,
            $helperPluginManager,
            $renderer,
            $authenticationService,
            $translator
        );

        $this->countryService = $countryService;
        $this->countrySearchService = $countrySearchService;
        $this->contactService = $contactService;
        $this->programService = $programService;
    }

    public function __invoke(Content $content): ?string
    {
        $params = $this->extractContentParam($content);

        $country = $this->getCountryByParams($params);

        switch ($content->getHandler()->getHandler()) {
            case 'country':
                if (null === $country) {
                    $this->response->setStatusCode(Response::STATUS_CODE_404);

                    return 'The selected country cannot be found';
                }

                $this->getHeadTitle()->append($this->translate('txt-country'));
                $this->getHeadTitle()->append($country->getCountry());

                $countryLink = $this->helperPluginManager->get(CountryLink::class);
                $this->getHeadMeta()->setProperty('og:type', $this->translate('txt-country'));
                $this->getHeadMeta()->setProperty('og:title', $country->getCountry());
                $this->getHeadMeta()->setProperty('og:url', $countryLink($country, 'view', LinkDecoration::SHOW_RAW));

                return $this->parseCountry($country);
            case 'country_list':
                return $this->parseCountryList();
            case 'country_list_itac':
                $this->getHeadTitle()->append($this->translate('txt-itac-countries-in-itea'));

                return $this->parseCountryListItac();
        }

        return null;
    }

    private function getCountryByParams(array $params): ?Country
    {
        $country = null;

        if (null !== $params['id']) {
            /** @var Country $country */
            $country = $this->countryService->find(Country::class, (int)$params['id']);
        }

        if (null !== $params['docRef']) {
            /** @var Country $country */
            $country = $this->countryService->findByCountryByDocRef($params['docRef']);
        }

        return $country;
    }

    private function parseCountry(Country $country): string
    {
        return $this->renderer->render(
            'cms/country/country',
            [
                'countrySearchResult' => $this->countrySearchService->findCountry($country),
                'country' => $country,
                'countryService' => $this->countryService,
                'contactService' => $this->contactService,
                'map' => $this->parseMap($country),
                'funder' => $this->programService->findFunderByCountry($country),
            ]
        );
    }

    private function parseMap(Country $country): string
    {
        $mapOptions = [
            'clickable' => true,
            'colorMin' => '#005C00',
            'colorMax' => '#00a651',
            'focusOn' => ['x' => 0.5, 'y' => 0.5, 'scale' => 1.1], // Slight zoom
            'height' => '600px',
        ];

        $countryMap = $this->helperPluginManager->get(CountryMap::class);

        return $countryMap([$country], null, $mapOptions);
    }

    private function parseCountryList(): string
    {
        return $this->renderer->render(
            'cms/country/list',
            [
                'countries' => $this->countrySearchService->findCountriesOnWebsite(),
                'countryService' => $this->countryService,
            ]
        );
    }

    private function parseCountryListItac(): string
    {
        return $this->renderer->render(
            'cms/country/list-itac',
            [
                'countries' => $this->countrySearchService->findItacCountries(),
                'countryService' => $this->countryService,
            ]
        );
    }
}
