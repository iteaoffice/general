<?php

/**
 * ITEA copyright message placeholder
 *
 * @category    ProjectTest
 * @package     Entity
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2019 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace GeneralTest\InputFilter;

use General\InputFilter\PasswordFilter;
use PHPUnit\Framework\TestCase;
use Laminas\InputFilter\InputFilter;

/**
 * Class GeneralTest
 *
 * @package GeneralTest\Entity
 */
class PasswordFilterTest extends TestCase
{
    /**
     *
     */
    public function testCanCreateInputFilter(): void
    {
        $inputFilter = new PasswordFilter();
        $this->assertInstanceOf(PasswordFilter::class, $inputFilter);
        $this->assertInstanceOf(InputFilter::class, $inputFilter);
    }
}
