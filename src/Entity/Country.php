<?php
/**
 * ITEA copyright message placeholder.
 *
 * @category  General
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Entity;

use Doctrine\Common\Collections;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Zend\Form\Annotation;

/**
 * Entity for the Country.
 *
 * @ORM\Table(name="country")
 * @ORM\Entity(repositoryClass="General\Repository\Country")
 * @Annotation\Hydrator("Zend\Hydrator\ObjectProperty")
 * @Annotation\Name("general_gender")
 *
 * @category General
 */
class Country extends AbstractEntity
{
    /**
     * @ORM\Column(name="country_id",type="integer",nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Annotation\Type("\Zend\Form\Element\Hidden")
     *
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(name="country_cd",type="string",length=2, unique=true)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-country-cd-label","help-block":"txt-country-cd-help-block"})
     *
     * @var string
     */
    private $cd;
    /**
     * @ORM\Column(name="country",type="string",length=80, unique=true)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-country-name-label","help-block":"txt-country-name-help-block"})
     *
     * @var string
     */
    private $country;
    /**
     * @ORM\Column(name="docRef",type="string",length=80, unique=true)
     * @Gedmo\Slug(fields={"country"})
     * @Annotation\Exclude()
     *
     * @var string
     */
    private $docRef;
    /**
     * @ORM\Column(name="iso3",type="string",length=20, nullable=true)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-country-iso3-label","help-block":"txt-country-iso3-help-block"})
     *
     * @var string
     */
    private $iso3;
    /**
     * @ORM\Column(name="numcode",type="integer",length=6)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-country-numcode-label","help-block":"txt-country-numcode-help-block"})
     *
     * @var int
     */
    private $numcode;
    /**
     * @ORM\Column(name="country_vat",type="string",length=2,nullable=true)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-country-vat-label","help-block":"txt-country-vat-help-block"})
     *
     * @var int
     */
    private $countryVat;
    /**
     * @ORM\OneToOne(targetEntity="General\Entity\Eu", cascade={"persist"}, mappedBy="country", fetch="EXTRA_LAZY")
     * @Annotation\Exclude()
     *
     * @var \General\Entity\Eu
     */
    private $eu;
    /**
     * @ORM\OneToOne(targetEntity="General\Entity\Eureka", cascade={"persist"}, mappedBy="country", fetch="EXTRA_LAZY")
     * @Annotation\Exclude()
     *
     * @var \General\Entity\Eureka
     */
    private $eureka;
    /**
     * @ORM\OneToOne(targetEntity="General\Entity\Itac", cascade={"persist"}, mappedBy="country", fetch="EXTRA_LAZY")
     * @Annotation\Exclude()
     *
     * @var \General\Entity\Eureka
     */
    private $itac;
    /**
     * @ORM\OneToMany(targetEntity="Contact\Entity\Address", cascade={"persist"}, mappedBy="country", fetch="EXTRA_LAZY")
     * @Annotation\Exclude()
     *
     * @var \Contact\Entity\Address[]|Collections\ArrayCollection
     */
    private $address;
    /**
     * @ORM\OneToMany(targetEntity="Organisation\Entity\Organisation", cascade={"persist"}, mappedBy="country")
     * @Annotation\Exclude()
     *
     * @var \Organisation\Entity\Organisation[]|Collections\ArrayCollection
     */
    private $organisation;
    /**
     * @ORM\OneToMany(targetEntity="Organisation\Entity\IctOrganisation", cascade={"persist"}, mappedBy="country")
     * @Annotation\Exclude()
     *
     * @var \Organisation\Entity\IctOrganisation[]|Collections\ArrayCollection
     */
    private $ictOrganisation;
    /**
     * @ORM\OneToMany(targetEntity="General\Entity\Vat", cascade={"persist"}, mappedBy="country")
     * @Annotation\Exclude()
     *
     * @var \General\Entity\Vat[]|Collections\ArrayCollection
     */
    private $vat;
    /**
     * @ORM\OneToMany(targetEntity="Program\Entity\Funder", cascade={"persist"}, mappedBy="country")
     * @Annotation\Exclude()
     *
     * @var \Program\Entity\Funder[]|Collections\ArrayCollection
     */
    private $funder;
    /**
     * @ORM\OneToOne(targetEntity="General\Entity\Flag", cascade={"persist"}, mappedBy="country", fetch="EXTRA_LAZY")
     * @Annotation\Exclude()
     *
     * @var \General\Entity\Flag
     */
    private $flag;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Evaluation\Evaluation", cascade={"persist"}, mappedBy="country")
     * @Annotation\Exclude()
     *
     * @var \Project\Entity\Evaluation\Evaluation[]|Collections\ArrayCollection
     */
    private $evaluation;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Rationale", cascade={"persist"}, mappedBy="country")
     * @Annotation\Exclude()
     *
     * @var \Project\Entity\Rationale[]|Collections\ArrayCollection
     */
    private $rationale;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\ChangeRequest\Country", cascade={"persist"}, mappedBy="country")
     * @Annotation\Exclude()
     *
     * @var \Project\Entity\ChangeRequest\Country[]|Collections\ArrayCollection
     */
    private $changeRequestCountry;
    /**
     * @ORM\ManyToMany(targetEntity="Project\Entity\Log", cascade={"persist"}, mappedBy="country")
     * @Annotation\Exclude()
     *
     * @var \Project\Entity\Log[]|Collections\ArrayCollection
     */
    private $projectLog;
    /**
     * @ORM\OneToMany(targetEntity="Program\Entity\Call\Country", cascade={"persist"}, mappedBy="country")
     * @Annotation\Exclude()
     *
     * @var \Program\Entity\Call\Country[]|Collections\ArrayCollection
     */
    private $callCountry;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Contract", cascade={"persist"}, mappedBy="country")
     * @Annotation\Exclude()
     *
     * @var \Project\Entity\Contract[]|Collections\ArrayCollection
     */
    private $contract;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->address = new Collections\ArrayCollection();
        $this->organisation = new Collections\ArrayCollection();
        $this->ictOrganisation = new Collections\ArrayCollection();
        $this->rationale = new Collections\ArrayCollection();
        $this->vat = new Collections\ArrayCollection();
        $this->funder = new Collections\ArrayCollection();
        $this->evaluation = new Collections\ArrayCollection();
        $this->changeRequestCountry = new Collections\ArrayCollection();
        $this->projectLog = new Collections\ArrayCollection();
        $this->callCountry = new Collections\ArrayCollection();
        $this->contract = new Collections\ArrayCollection();
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
     * @param $property
     *
     * @return bool
     */
    public function __isset($property)
    {
        return isset($this->$property);
    }

    /**
     * toString returns the name.
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->country;
    }


    /**
     * New function needed to make the hydrator happy.
     *
     * @param Collections\Collection $vatCollection
     */
    public function addVat(Collections\Collection $vatCollection)
    {
        foreach ($vatCollection as $vat) {
            $this->vat->add($vat);
        }
    }

    /**
     * New function needed to make the hydrator happy.
     *
     * @param Collections\Collection $vatCollection
     */
    public function removeVat(Collections\Collection $vatCollection)
    {
        foreach ($vatCollection as $vat) {
            $this->vat->removeElement($vat);
        }
    }

    /**
     * @return string
     */
    public function getCd()
    {
        return $this->cd;
    }

    /**
     * @param string $cd
     */
    public function setCd($cd)
    {
        $this->cd = $cd;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return \General\Entity\Eu
     */
    public function getEu()
    {
        return $this->eu;
    }

    /**
     * @param \General\Entity\Eu $eu
     */
    public function setEu($eu)
    {
        $this->eu = $eu;
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
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getIso3()
    {
        return $this->iso3;
    }

    /**
     * @param string $iso3
     */
    public function setIso3($iso3)
    {
        $this->iso3 = $iso3;
    }

    /**
     * @return int
     */
    public function getNumcode()
    {
        return $this->numcode;
    }

    /**
     * @param int $numcode
     */
    public function setNumcode($numcode)
    {
        $this->numcode = $numcode;
    }

    /**
     * @return Collections\ArrayCollection|Vat[]
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * @param $vat
     */
    public function setVat($vat)
    {
        $this->vat = $vat;
    }

    /**
     * @return \Contact\Entity\Address[]|Collections\ArrayCollection
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param \Contact\Entity\Address[]|Collections\ArrayCollection $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return \Organisation\Entity\IctOrganisation[]|Collections\ArrayCollection
     */
    public function getIctOrganisation()
    {
        return $this->ictOrganisation;
    }

    /**
     * @param \Organisation\Entity\IctOrganisation[]|Collections\ArrayCollection $ictOrganisation
     */
    public function setIctOrganisation($ictOrganisation)
    {
        $this->ictOrganisation = $ictOrganisation;
    }

    /**
     * @return \Organisation\Entity\Organisation[]|Collections\ArrayCollection
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }

    /**
     * @param \Organisation\Entity\Organisation[]|Collections\ArrayCollection $organisation
     */
    public function setOrganisation($organisation)
    {
        $this->organisation = $organisation;
    }

    /**
     * @return int
     */
    public function getCountryVat()
    {
        return $this->countryVat;
    }

    /**
     * @param int $countryVat
     */
    public function setCountryVat($countryVat)
    {
        $this->countryVat = $countryVat;
    }

    /**
     * @return \Program\Entity\Funder[]|Collections\ArrayCollection
     */
    public function getFunder()
    {
        return $this->funder;
    }

    /**
     * @param \Program\Entity\Funder[]|Collections\ArrayCollection $funder
     */
    public function setFunder($funder)
    {
        $this->funder = $funder;
    }

    /**
     * @return \General\Entity\Eureka
     */
    public function getEureka()
    {
        return $this->eureka;
    }

    /**
     * @param \General\Entity\Eureka $eureka
     */
    public function setEureka($eureka)
    {
        $this->eureka = $eureka;
    }

    /**
     * @return \General\Entity\Flag
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * @param \General\Entity\Flag $flag
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;
    }

    /**
     * @return \General\Entity\Eureka
     */
    public function getItac()
    {
        return $this->itac;
    }

    /**
     * @param \General\Entity\Eureka $itac
     */
    public function setItac($itac)
    {
        $this->itac = $itac;
    }

    /**
     * @return string
     */
    public function getDocRef()
    {
        return $this->docRef;
    }

    /**
     * @param string $docRef
     */
    public function setDocRef($docRef)
    {
        $this->docRef = $docRef;
    }

    /**
     * @return \Project\Entity\Evaluation\Evaluation[]|Collections\ArrayCollection
     */
    public function getEvaluation()
    {
        return $this->evaluation;
    }

    /**
     * @param \Project\Entity\Evaluation\Evaluation[]|Collections\ArrayCollection $evaluation
     */
    public function setEvaluation($evaluation)
    {
        $this->evaluation = $evaluation;
    }

    /**
     * @return Collections\ArrayCollection|\Project\Entity\Rationale[]
     */
    public function getRationale()
    {
        return $this->rationale;
    }

    /**
     * @param Collections\ArrayCollection|\Project\Entity\Rationale[] $rationale
     */
    public function setRationale($rationale)
    {
        $this->rationale = $rationale;
    }

    /**
     * @return Collections\ArrayCollection|\Project\Entity\ChangeRequest\Country[]
     */
    public function getChangeRequestCountry()
    {
        return $this->changeRequestCountry;
    }

    /**
     * @param Collections\ArrayCollection|\Project\Entity\ChangeRequest\Country[] $changeRequestCountry
     *
     * @return Country
     */
    public function setChangeRequestCountry($changeRequestCountry): Country
    {
        $this->changeRequestCountry = $changeRequestCountry;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\Project\Entity\Log[]
     */
    public function getProjectLog()
    {
        return $this->projectLog;
    }

    /**
     * @param Collections\ArrayCollection|\Project\Entity\Log[] $projectLog
     *
     * @return Country
     */
    public function setProjectLog($projectLog)
    {
        $this->projectLog = $projectLog;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\Program\Entity\Call\Country[]
     */
    public function getCallCountry()
    {
        return $this->callCountry;
    }

    /**
     * @param Collections\ArrayCollection|\Program\Entity\Call\Country[] $callCountry
     *
     * @return Country
     */
    public function setCallCountry($callCountry)
    {
        $this->callCountry = $callCountry;

        return $this;
    }

    /**
     * @return Collections\ArrayCollection|\Project\Entity\Contract[]
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * @param Collections\ArrayCollection|\Project\Entity\Contract[] $contract
     *
     * @return Country
     */
    public function setContract($contract)
    {
        $this->contract = $contract;

        return $this;
    }
}
