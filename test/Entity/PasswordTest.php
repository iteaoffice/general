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

use General\Entity\AbstractEntity;
use General\Entity\Password;
use PHPUnit\Framework\TestCase;

/**
 * Class GeneralTest
 *
 * @package GeneralTest\Entity
 */
class PasswordTest extends TestCase
{
    /**
     *
     */
    public function testCanCreatePassword(): void
    {
        $password = new Password();
        $this->assertInstanceOf(Password::class, $password);
        $this->assertInstanceOf(AbstractEntity::class, $password);
    }
}
