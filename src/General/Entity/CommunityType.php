<?php
/**
 * ITEA copyright message placeholder
 *
 * @category    General
 * @package     Entity
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
 */
namespace General\Entity;

use Doctrine\Common\Collections;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;

/**
 * Entity for the Community Type.
 *
 * @ORM\Table(name="contact_community_type")
 * @ORM\Entity
 *
 * @category    General
 * @package     Entity
 */
class CommunityType extends EntityAbstract
{
    /**
     * @ORM\Column(name="type_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(name="type", type="string", unique=true)
     * @var string
     */
    private $type;
    /**
     * @ORM\Column(name="regularexpression", type="string", unique=true)
     * @var string
     */
    private $regularExpression;
    /**
     * @ORM\Column(name="link", type="string", unique=true)
     * @var string
     */
    private $link;
    /**
     * @ORM\Column(name="image", type="string", unique=true)
     * @var string
     */
    private $image;
    /**
     * @ORM\OneToMany(targetEntity="Contact\Entity\Community", cascade={"persist"}, mappedBy="type")
     * @var \Contact\Entity\Community[]
     */
    private $community;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->community = new Collections\ArrayCollection();
    }

    /**
     * Magic Getter
     *
     * @param $property
     *
     * @return mixed
     */
    public function __get($property)
    {
        return $this->$property;
    }

    /**
     * Magic Setter
     *
     * @param $property
     * @param $value
     *
     * @return void
     */
    public function __set($property, $value)
    {
        $this->$property = $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->type;
    }

    /**
     * Set input filter
     *
     * @param InputFilterInterface $inputFilter
     *
     * @return void
     * @throws \Exception
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Setting an inputFilter is currently not supported");
    }

    /**
     * @return \Zend\InputFilter\InputFilter|\Zend\InputFilter\InputFilterInterface
     */
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add(
                $factory->createInput(
                    array(
                        'name'     => 'type',
                        'required' => true,
                    )
                )
            );

            $inputFilter->add(
                $factory->createInput(
                    array(
                        'name'     => 'regularExpression',
                        'required' => true,
                    )
                )
            );

            $inputFilter->add(
                $factory->createInput(
                    array(
                        'name'     => 'link',
                        'required' => true,
                    )
                )
            );

            $inputFilter->add(
                $factory->createInput(
                    array(
                        'name'     => 'image',
                        'required' => false,
                    )
                )
            );

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    /**
     * Needed for the hydration of form elements
     *
     * @return array
     */
    public function getArrayCopy()
    {
        return array(
            'id'                => $this->id,
            'type'              => $this->type,
            'regularExpression' => $this->regularExpression,
            'link'              => $this->link,
            'image'             => $this->image,
        );
    }

    /**
     * Function needed for the population of forms
     *
     * @return array
     */
    public function populate()
    {
        return $this->getArrayCopy();
    }

    /**
     * @param \Contact\Entity\Community[] $community
     */
    public function setCommunity($community)
    {
        $this->community = $community;
    }

    /**
     * @return \Contact\Entity\Community[]
     */
    public function getCommunity()
    {
        return $this->community;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $regularExpression
     */
    public function setRegularExpression($regularExpression)
    {
        $this->regularExpression = $regularExpression;
    }

    /**
     * @return string
     */
    public function getRegularExpression()
    {
        return $this->regularExpression;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
