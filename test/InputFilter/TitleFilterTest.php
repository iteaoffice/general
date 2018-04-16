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

namespace GeneralTest\InputFilter;

use General\Entity;
use General\InputFilter\TitleFilter;
use General\Repository;
use Testing\Util\AbstractServiceTest;
use Zend\InputFilter\InputFilter;

/**
 * Class GeneralTest
 *
 * @package GeneralTest\Entity
 */
class TitleFilterTest extends AbstractServiceTest
{
    /**
     *
     */
    public function testCanCreateInputFilter(): void
    {
        $contactRepositoryMock = $this->getMockBuilder(Repository\Title::class)
            ->disableOriginalConstructor()->getMock();

        $entityManager = $this->getEntityManagerMock(Entity\Title::class, $contactRepositoryMock);

        $inputFilter = new TitleFilter($entityManager);
        $this->assertInstanceOf(TitleFilter::class, $inputFilter);
        $this->assertInstanceOf(InputFilter::class, $inputFilter);
    }
}
