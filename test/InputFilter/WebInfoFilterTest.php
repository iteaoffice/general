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

use General\Entity;
use General\InputFilter\WebInfoFilter;
use General\Repository;
use Testing\Util\AbstractServiceTest;
use Laminas\InputFilter\InputFilter;

/**
 * Class GeneralTest
 *
 * @package GeneralTest\Entity
 */
class WebInfoFilterTest extends AbstractServiceTest
{
    /**
     *
     */
    public function testCanCreateInputFilter(): void
    {
        $contactRepositoryMock = $this->getMockBuilder(Repository\WebInfo::class)
            ->disableOriginalConstructor()->getMock();

        $entityManager = $this->getEntityManagerMock(Entity\WebInfo::class, $contactRepositoryMock);

        $inputFilter = new WebInfoFilter($entityManager);
        $this->assertInstanceOf(WebInfoFilter::class, $inputFilter);
        $this->assertInstanceOf(InputFilter::class, $inputFilter);
    }
}
