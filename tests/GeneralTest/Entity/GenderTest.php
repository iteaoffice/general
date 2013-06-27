<?php
/**
 * ITEA copyright message placeholder
 *
 * @category    ContactTest
 * @package     Entity
 * @author      Johan van der Heide <info@japaveh.nl>
 * @copyright   Copyright (c) 2004-2013 ITEA
 */
namespace ContactTest\Entity;

use General\Entity\Gender;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;


class GenderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Gender
     */
    protected $gender;


    public function setUp()
    {
        parent::setUp();

        $this->gender = new Gender;
    }


    public function testCanCreateEntity()
    {
        $this->assertInstanceOf("General\Entity\Gender", $this->gender);
    }

    public function testHasFilter()
    {
        return $this->assertInstanceOf('Zend\InputFilter\InputFilter', $this->gender->getInputFilter());
    }

}
