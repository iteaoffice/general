<?php
/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 * @category    News
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        https://github.com/iteaoffice/news for the canonical source repository
 */

declare(strict_types=1);

namespace General\Search\Service;

use General\Entity\Country;
use Search\Service\AbstractSearchService;
use Search\Service\SearchServiceInterface;
use Solarium\Core\Query\Result\ResultInterface;
use Solarium\QueryType\Select\Query\Query;

/**
 * Class CountrySearchService
 *
 * @package General\Search\Service
 */
final class CountrySearchService extends AbstractSearchService
{
    public const SOLR_CONNECTION = 'general_country';

    public function setSearch(
        string $searchTerm,
        array $searchFields = [],
        string $order = '',
        string $direction = Query::SORT_ASC
    ): SearchServiceInterface {
        $this->setQuery($this->getSolrClient()->createSelect());
        $this->getQuery()->setQuery(static::parseQuery($searchTerm, $searchFields));

        $hasTerm = !\in_array($searchTerm, ['*', ''], true);
        $hasSort = ($order !== '');

        if ($hasSort) {
            switch ($order) {
                default:
                    $this->getQuery()->addSort('date_published', Query::SORT_DESC);
                    break;
            }
        }

        if ($hasTerm) {
            $this->getQuery()->addSort('country_sort', Query::SORT_DESC);
        } else {
            $this->getQuery()->addSort('country_sort', Query::SORT_DESC);
        }

        $facetSet = $this->getQuery()->getFacetSet();
        $facetSet->createFacetField('is_itac_text')->setField('is_itac_text')->setSort('is_itac_text')->setMinCount(1)
            ->setExcludes(['is_itac_text']);

        return $this;
    }

    public function findCountriesOnWebsite(): ResultInterface
    {
        $this->setQuery($this->getSolrClient()->createSelect());

        //Add the 'on_website' constraint
        $query = '(has_projects:true)';

        $this->query->setQuery($query);
        $this->query->addSort('country_sort', Query::SORT_ASC);
        $this->query->setRows(20000);

        return $this->getSolrClient()->execute($this->query);
    }

    public function findItacCountries(): ResultInterface
    {
        $this->setQuery($this->getSolrClient()->createSelect());

        //Add the 'on_website' constraint
        $query = '(is_itac:true)';

        $this->query->setQuery($query);
        $this->query->addSort('country_sort', Query::SORT_ASC);

        return $this->getSolrClient()->execute($this->query);
    }

    public function findCountry(Country $country): ?array
    {
        $this->setQuery($this->getSolrClient()->createSelect());

        //Add the 'on_website' constraint
        $query = '(country_id:' . $country->getId() . ')';

        $this->query->setQuery($query);
        $this->query->addSort('country_sort', Query::SORT_ASC);
        $this->query->setRows(1);

        /** @var ResultInterface $countryResult */
        foreach ($this->getSolrClient()->execute($this->query) as $countryResult) {
            return $countryResult->getFields();
        }

        return null;
    }
}