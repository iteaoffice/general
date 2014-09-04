<?php
/**
 * ITEA Office copyright message placeholder
 *
 * @category  Organisation
 * @package   Service
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
 */
namespace General\Service;

use General\Entity\EntityAbstract;

interface ServiceInterface
{
    /**
     * @return string
     */
    public function getFullEntityName($entity);

    /**
     * @return EntityAbstract
     */
    public function updateEntity(EntityAbstract $entity);

    /**
     * @return EntityAbstract
     */
    public function newEntity(EntityAbstract $entity);

    public function getEntityManager();

    public function findAll($entity);
}
