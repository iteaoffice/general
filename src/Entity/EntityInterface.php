<?php
/**
 * ITEA Office all rights reserved
 *
 * @category  General
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Entity;

/**
 * Interface EntityInterface
 *
 * @package General\Entity
 */
interface EntityInterface
{
    public function __get($property);

    public function __set($property, $value);

    public function getId();
}
