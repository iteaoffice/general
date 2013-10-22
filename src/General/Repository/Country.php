<?php
/**
 * DebraNova copyright message placeholder
 *
 * @category    Contact
 * @package     Repository
 * @author      Johan van der Heide <info@japaveh.nl>
 * @copyright   Copyright (c) 2004-2013 Japaveh Webdesign (http://japaveh.nl)
 */
namespace General\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

use Project\Entity\VersionType;
use Project\Entity\Version;

use General\Entity;

/**
 * @category    Contact
 * @package     Repository
 */
class Country extends EntityRepository
{
    /**
     * @return Entity\Country[];
     */
    public function findActive()
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('c');
        $queryBuilder->from('General\Entity\Country', 'c');

        $queryBuilder->join('c.organisation', 'o');
        $queryBuilder->join('o.affiliation', 'a');

        //Remove the 0 country (unknown)
        $queryBuilder->where('c.id <> 0');

        //Limit to only the active projects
        $projectRepository = $this->getEntityManager()->getRepository('Project\Entity\Project');
        $queryBuilder      = $projectRepository->onlyActiveProject($queryBuilder);

        return $queryBuilder->getQuery()->getResult();
    }
}