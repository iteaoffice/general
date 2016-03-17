<?php
/**
 * ITEA Office copyright message placeholder.
 *
 * @category  Organisation
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2015 ITEA Office (https://itea3.org)
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
