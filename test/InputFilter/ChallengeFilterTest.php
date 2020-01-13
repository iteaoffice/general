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
use General\InputFilter\ChallengeFilter;
use General\Repository;
use Testing\Util\AbstractServiceTest;
use Laminas\InputFilter\InputFilter;

/**
 * Class GeneralTest
 *
 * @package GeneralTest\Entity
 */
class ChallengeFilterTest extends AbstractServiceTest
{
    /**
     *
     */
    public function testCanCreateInputFilter(): void
    {
        $contactRepositoryMock = $this->getMockBuilder(Repository\Challenge::class)
            ->disableOriginalConstructor()->getMock();

        $entityManager = $this->getEntityManagerMock(Entity\Challenge::class, $contactRepositoryMock);

        $inputFilter = new ChallengeFilter($entityManager);
        $this->assertInstanceOf(ChallengeFilter::class, $inputFilter);
        $this->assertInstanceOf(InputFilter::class, $inputFilter);
    }
}
