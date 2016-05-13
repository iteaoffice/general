<?php
/**
 * ITEA Office copyright message placeholder.
 *
 * @category  General
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2015 ITEA Office (https://itea3.org)
 */

namespace General\Entity;

use Doctrine\Common\Collections;
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterInterface;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Entity for the General.
 *
 * @ORM\Table(name="gender")
 * @ORM\Entity(repositoryClass="General\Repository\Gender")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @Annotation\Name("general_gender")
 *
 * @category General
 */
class Gender extends EntityAbstract implements ResourceInterface
{
    /**
     * Constant for the default gender.
     */
    const GENDER_UNKNOWN = 0;
    /**
     * @ORM\Column(name="gender_id",type="integer",length=10,nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Exclude()
     *
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(name="gender",type="string",length=20,unique=true)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-gender"})
     *
     * @var string
     */
    private $name;
    /**
     * @ORM\Column(name="attention",type="string",length=20)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-attention"})
     *
     * @var string
     */
    private $attention;
    /**
     * @ORM\Column(name="salutation",type="string",length=20)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-salutation"})
     *
     * @var string
     */
    private $salutation;
    /**
     * @ORM\OneToMany(targetEntity="Contact\Entity\Contact", cascade={"all"}, mappedBy="gender")
     * @Annotation\Exclude()
     *
     * @var \Contact\Entity\Contact[]
     */
    private $contacts;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->contacts = new Collections\ArrayCollection();
    }

    /**
     * Magic Getter.
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
     * Magic Setter.
     *
     * @param $property
     * @param $value
     */
    public function __set($property, $value)
    {
        $this->$property = $value;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Gender
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Gender
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getAttention()
    {
        return $this->attention;
    }

    /**
     * @param string $attention
     *
     * @return Gender
     */
    public function setAttention($attention)
    {
        $this->attention = $attention;

        return $this;
    }

    /**
     * @return string
     */
    public function getSalutation()
    {
        return $this->salutation;
    }

    /**
     * @param string $salutation
     *
     * @return Gender
     */
    public function setSalutation($salutation)
    {
        $this->salutation = $salutation;

        return $this;
    }

    /**
     * @return \Contact\Entity\Contact[]
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * @param \Contact\Entity\Contact[] $contacts
     *
     * @return Gender
     */
    public function setContacts($contacts)
    {
        $this->contacts = $contacts;

        return $this;
    }
}
