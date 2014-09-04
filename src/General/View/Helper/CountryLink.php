<?php

/**
 * ITEA Office copyright message placeholder
 *
 * @category   General
 * @package    View
 * @subpackage Helper
 * @author     Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright  Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
 */
namespace General\View\Helper;

use General\Entity\Country;

/**
 * Create a link to an country
 *
 * @category   General
 * @package    View
 * @subpackage Helper
 */
class CountryLink extends LinkAbstract
{
    /**
     * @var Country
     */
    protected $country;

    /**
     * @param Country $country
     * @param string  $action
     * @param string  $show
     * @param string  $alternativeShow
     *
     * @return string
     * @throws \Exception
     */
    public function __invoke(
        Country $country,
        $action = 'view',
        $show = 'name',
        $alternativeShow = null
    ) {
        $this->setCountry($country);
        $this->setAction($action);
        $this->setShow($show);
        $this->setAlternativeShow($alternativeShow);
        $this->addRouterParam('id', $country->getId());
        $this->addRouterParam('docRef', $country->getDocRef());
        $this->setShowOptions(
            [
                'name'   => $country,
                'more'   => $this->translate("txt-read-more"),
                'custom' => $this->getAlternativeShow(),
                'flag'   => $this->getCountryFlag($country, 40)
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
            $this->setRouter('route-' . $this->getCountry()->get('underscore_full_entity_name'));
            $this->setText(sprintf($this->translate("txt-view-country-%s"), $this->getCountry()));
            break;
        case 'view-project':
            $this->setRouter('route-' . $this->getCountry()->get('underscore_full_entity_name') . '-project');
            $this->setText(sprintf($this->translate("txt-view-project-for-country-%s"), $this->getCountry()));
            break;
        case 'view-organisation':
            $this->setRouter('route-' . $this->getCountry()->get('underscore_full_entity_name') . '-organisation');
            $this->setText(sprintf($this->translate("txt-view-organisation-for-country-%s"), $this->getCountry()));
            break;
        case 'view-article':
            $this->setRouter('route-' . $this->getCountry()->get('underscore_full_entity_name') . '-article');
            $this->setText(sprintf($this->translate("txt-view-article-for-country-%s"), $this->getCountry()));
            break;
        default:
            throw new \InvalidArgumentException(
                sprintf("%s is an incorrect action for %s", $this->getAction(), __CLASS__)
            );
        }
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param Country $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }
}
