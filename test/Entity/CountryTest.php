<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace GeneralTest\Entity;

use General\Entity\AbstractEntity;
use General\Entity\Country;
use PHPUnit\Framework\TestCase;

/**
 * Class GeneralTest
 *
 * @package GeneralTest\Entity
 */
class CountryTest extends TestCase
{
    /**
     *
     */
    public function testCanCreateCountry(): void
    {
        $country = new Country();
        $this->assertInstanceOf(Country::class, $country);
        $this->assertInstanceOf(AbstractEntity::class, $country);
    }
}
