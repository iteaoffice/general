<?php
/**
 * ITEA copyright message placeholder.
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
 * @ORM\Table(name="title")
 * @ORM\Entity(repositoryClass="General\Repository\Title")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @Annotation\Name("general_gender")
 *
 * @category General
 */
class Title extends EntityAbstract implements ResourceInterface
{
    /**
     * Constant for the default title.
     */
    const TITLE_UNKNOWN = 0;
    /**
     * @ORM\Column(name="title_id",type="integer",nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Exclude()
     *
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(name="title",type="string",length=20, unique=true)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-title"})
     *
     * @var string
     */
    private $name;
    /**
     * @ORM\Column(name="attention",type="string",length=20, unique=true)
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
     * @ORM\OneToMany(targetEntity="Contact\Entity\Contact", cascade={"persist"}, mappedBy="title")
     * @Annotation\Exclude()
     *
     * @var \Contact\Entity\Contact[]
     */
    private $contacts;
    /**
     * @ORM\OneToMany(targetEntity="Partner\Entity\Applicant", cascade={"persist"}, mappedBy="applicantTitle")
     * @Annotation\Exclude()
     *
     * @var \Partner\Entity\Applicant[]|Collections\ArrayCollection
     */
    private $applicantTitle;
    /**
     * @ORM\OneToMany(targetEntity="Partner\Entity\Applicant", cascade={"persist"}, mappedBy="contactTitle")
     * @Annotation\Exclude()
     *
     * @var \Partner\Entity\Applicant[]|Collections\ArrayCollection
     */
    private $applicantContactTitle;
    /**
     * @ORM\OneToMany(targetEntity="Partner\Entity\Applicant", cascade={"persist"}, mappedBy="financialTitle")
     * @Annotation\Exclude()
     *
     * @var \Partner\Entity\Applicant[]|Collections\ArrayCollection
     */
    private $applicantFinancialTitle;


    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->contacts = new Collections\ArrayCollection();
        $this->applicantTitle = new Collections\ArrayCollection();
        $this->applicantContactTitle = new Collections\ArrayCollection();
        $this->applicantFinancialTitle = new Collections\ArrayCollection();
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
     * toString returns the name.
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->name;
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
     * @return Title
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
     * @return Title
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
     * @return Title
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
     * @return Title
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
     * @return Title
     */
    public function setContacts($contacts)
    {
        $this->contacts = $contacts;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\Partner\Entity\Applicant[]
     */
    public function getApplicantTitle()
    {
        return $this->applicantTitle;
    }

    /**
     * @param Collections\ArrayCollection|\Partner\Entity\Applicant[] $applicantTitle
     *
     * @return Title
     */
    public function setApplicantTitle($applicantTitle)
    {
        $this->applicantTitle = $applicantTitle;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\Partner\Entity\Applicant[]
     */
    public function getApplicantContactTitle()
    {
        return $this->applicantContactTitle;
    }

    /**
     * @param Collections\ArrayCollection|\Partner\Entity\Applicant[] $applicantContactTitle
     *
     * @return Title
     */
    public function setApplicantContactTitle($applicantContactTitle)
    {
        $this->applicantContactTitle = $applicantContactTitle;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\Partner\Entity\Applicant[]
     */
    public function getApplicantFinancialTitle()
    {
        return $this->applicantFinancialTitle;
    }

    /**
     * @param Collections\ArrayCollection|\Partner\Entity\Applicant[] $applicantFinancialTitle
     *
     * @return Title
     */
    public function setApplicantFinancialTitle($applicantFinancialTitle)
    {
        $this->applicantFinancialTitle = $applicantFinancialTitle;

        return $this;
    }
}
