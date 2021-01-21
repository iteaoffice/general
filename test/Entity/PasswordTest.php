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
