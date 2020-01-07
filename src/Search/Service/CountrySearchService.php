<?php
/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 * @category    News
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2019 ITEA Office (https://itea3.org)
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
use function in_array;

/**
 * Class CountrySearchService
 *
 * @package General\Search\Service
 */
class CountrySearchService extends AbstractSearchService
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

        $hasTerm = ! in_array($searchTerm, ['*', ''], true);
        $hasSort = ($order !== '');

        if ($hasSort) {
            switch ($order) {
                case 'id':
                    $this->getQuery()->addSort('country_id', $direction);
                    break;
                case 'name':
                    $this->getQuery()->addSort('country_sort', $direction);
                    break;
                case 'cd':
                    $this->getQuery()->addSort('country_cd', $direction);
                    break;
                case 'iso3':
                    $this->getQuery()->addSort('country_iso3', $direction);
                    break;
                case 'is_itac':
                    $this->getQuery()->addSort('is_itac_text', $direction);
                    break;
                case 'is_eu':
                    $this->getQuery()->addSort('is_eu_text', $direction);
                    break;
                case 'is_eureka':
                    $this->getQuery()->addSort('is_eureka_text', $direction);
                    break;
                case 'funders':
                    $this->getQuery()->addSort('funders', $direction);
                    break;
                default:
                    $this->getQuery()->addSort('country_sort', Query::SORT_ASC);
                    break;
            }
        }

        if ($hasTerm) {
            $this->getQuery()->addSort('country_sort', Query::SORT_DESC);
        } else {
            $this->getQuery()->addSort('country_sort', Query::SORT_ASC);
        }

        $facetSet = $this->getQuery()->getFacetSet();
        $facetSet->createFacetField('Eu')->setField('is_eu_text')->setSort('index')->setMinCount(1)
            ->setExcludes(['is_eu_text']);
        $facetSet->createFacetField('itac')->setField('is_itac_text')->setSort('index')->setMinCount(1)
            ->setExcludes(['is_itac_text']);
        $facetSet->createFacetField('has_projects')->setField('has_projects_text')->setSort('index')->setMinCount(1)
            ->setExcludes(['has_projects_text']);
        $facetSet->createFacetField('has_partners')->setField('has_affiliations_text')->setSort('index')->setMinCount(1)
            ->setExcludes(['has_affiliations_text']);
        $facetSet->createFacetField('has_public_authorities')->setField('has_funders_text')->setSort('index')
            ->setMinCount(1)
            ->setExcludes(['has_funders_text']);

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

    public function findAmountOfActiveCountries(): int
    {
        $this->setQuery($this->getSolrClient()->createSelect());

        //Add the 'on_website' constraint
        $query = '(has_projects:true)';
        $this->getQuery()->setQuery($query);


        $result = $this->getSolrClient()->execute($this->query);

        return (int)($result->getData()['response']['numFound'] ?? 0);
    }

    public function findItacCountries(): ResultInterface
    {
        $this->setQuery($this->getSolrClient()->createSelect());

        //Add the 'on_website' constraint
        $query = '(is_itac:true)';

        $this->query->setQuery($query);
        $this->query->addSort('country_sort', Query::SORT_ASC);
        $this->query->setRows(25);

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
