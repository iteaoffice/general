<?php
/**
 * ITEA copyright message placeholder
 *
 * @category    ProjectTest
 * @package     Entity
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace GeneralTest\Entity;

use General\Entity\Country;
use General\Entity\EntityAbstract;
use PHPUnit\Framework\TestCase;

/**
 * Class GeneralTest
 *
 * @package GeneralTest\Entity
 */
class GeneralTest extends TestCase
{
    /**
     *
     */
    public function testCanCreateCountry()
    {
        $country = new Country();
        $this->assertInstanceOf(Country::class, $country);
        $this->assertInstanceOf(EntityAbstract::class, $country);
    }
}
