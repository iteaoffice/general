<?php

/**
 * ITEA Office copyright message placeholder.
 *
 * @category   General
 *
 * @author     Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright  Copyright (c) 2004-2015 ITEA Office (https://itea3.org)
 */

namespace General\View\Helper;

use General\Entity\Country;

/**
 * Create a link to an country.
 *
 * @category   General
 */
class CountryLink extends LinkAbstract
{
    /**
     * @var Country
     */
    protected $country;

    /**
     * @param Country $country
     * @param string $action
     * @param string $show
     * @param string $alternativeShow
     *
     * @return string
     *
     * @throws \Exception
     */
    public function __invoke(
        Country $country = null,
        $action = 'view',
        $show = 'name',
        $alternativeShow = null
    ) {
        $this->setCountry($country);
        $this->setAction($action);
        $this->setShow($show);
        $this->setAlternativeShow($alternativeShow);
        $this->addRouterParam('id', $this->getCountry()->getId());
        $this->addRouterParam('docRef', $this->getCountry()->getDocRef());
        $this->setShowOptions(
            [
                'name'   => $this->getCountry(),
                'more'   => $this->translate("txt-read-more"),
                'custom' => $this->getAlternativeShow(),
                'flag'   => $this->getCountryFlag($this->getCountry(), 40),
            ]
        );

        return $this->createLink();
    }

    /**
     * @return string|void
     */
    public function parseAction()
    {
        switch ($this->getAction()) {
            case 'view':
                $this->setRouter('route-' . $this->getCountry()->get('underscore_entity_name'));
                $this->setText(sprintf($this->translate("txt-view-country-%s"), $this->getCountry()));
                break;
            case 'view-project':
                $this->setRouter('route-' . $this->getCountry()->get('underscore_entity_name') . '-project');
                $this->setText(sprintf($this->translate("txt-view-project-for-country-%s"), $this->getCountry()));
                break;
            case 'view-organisation':
                $this->setRouter('route-' . $this->getCountry()->get('underscore_entity_name') . '-organisation');
                $this->setText(sprintf($this->translate("txt-view-organisation-for-country-%s"), $this->getCountry()));
                break;
            case 'view-article':
                $this->setRouter('route-' . $this->getCountry()->get('underscore_entity_name') . '-article');
                $this->setText(sprintf($this->translate("txt-view-article-for-country-%s"), $this->getCountry()));
                break;
            case 'list':
                $this->setRouter('zfcadmin/country/list');
                $this->setText(sprintf($this->translate('txt-country-list')));
                break;
            case 'new':
                $this->setRouter('zfcadmin/country/new');
                $this->setText(sprintf($this->translate('txt-create-new-country')));
                break;
            case 'view-admin':
                $this->setRouter('zfcadmin/country/view');
                $this->setText(sprintf($this->translate('txt-view-country-%s'), $this->getCountry()));
                break;
            case 'edit':
                $this->setRouter('zfcadmin/country/edit');
                $this->setText(sprintf($this->translate('txt-edit-country-%s'), $this->getCountry()));
                break;
            default:
                throw new \InvalidArgumentException(
                    sprintf("%s is an incorrect action for %s", $this->getAction(), __CLASS__)
                );
        }
    }
}
