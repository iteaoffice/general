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

use Contact\Entity\Address;
use Doctrine\Common\Collections;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use News\Entity\Magazine\Article;
use Organisation\Entity\Organisation;
use Program\Entity\Funder;
use Project\Entity\Contract;
use Evaluation\Entity\Evaluation;
use Project\Entity\Rationale;
use Zend\Form\Annotation;

/**
 * @ORM\Table(name="country")
 * @ORM\Entity(repositoryClass="General\Repository\Country")
 * @Annotation\Hydrator("Zend\Hydrator\ObjectProperty")
 * @Annotation\Name("general_gender")
 */
class Country extends AbstractEntity
{
    /**
     * @ORM\Column(name="country_id",type="integer",options={"unsigned":true})
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
     * @Annotation\Attributes({"placeholder":"txt-country-cd-placeholder"})
     *
     * @var string
     */
    private $cd;
    /**
     * @ORM\Column(name="country",type="string",unique=true)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-country-name-label","help-block":"txt-country-name-help-block"})
     * @Annotation\Attributes({"placeholder":"txt-country-name-placeholder"})
     *
     * @var string
     */
    private $country;
    /**
     * @ORM\Column(name="docRef",type="string",unique=true)
     * @Gedmo\Slug(fields={"country"})
     * @Annotation\Exclude()
     *
     * @var string
     */
    private $docRef;
    /**
     * @ORM\Column(name="iso3",type="string",nullable=true)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-country-iso3-label","help-block":"txt-country-iso3-help-block"})
     * @Annotation\Attributes({"placeholder":"txt-country-iso3-placeholder"})
     *
     * @var string
     */
    private $iso3;
    /**
     * @ORM\Column(name="numcode",type="integer",length=6)
     * @Annotation\Type("\Zend\Form\Element\Text")
     * @Annotation\Options({"label":"txt-country-numcode-label","help-block":"txt-country-numcode-help-block"})
     * @Annotation\Attributes({"placeholder":"txt-country-numcode-placeholder"})
     *
     * @var int
     */
    private $numcode;
    /**
     * @ORM\Column(name="country_vat",type="string",nullable=true)
     * @Annotation\Type("\Zend\Form\Element\Number")
     * @Annotation\Options({"label":"txt-country-vat-label","help-block":"txt-country-vat-help-block"})
     * @Annotation\Attributes({"placeholder":"txt-country-vat-placeholder"})
     *
     * @var string
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
     * @var \General\Entity\Itac
     */
    private $itac;
    /**
     * @ORM\OneToMany(targetEntity="Contact\Entity\Address", cascade={"persist"}, mappedBy="country", fetch="EXTRA_LAZY")
     * @Annotation\Exclude()
     *
     * @var Address[]|Collections\ArrayCollection
     */
    private $address;
    /**
     * @ORM\OneToMany(targetEntity="Organisation\Entity\Organisation", cascade={"persist"}, mappedBy="country")
     * @Annotation\Exclude()
     *
     * @var Organisation[]|Collections\ArrayCollection
     */
    private $organisation;
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
     * @var Funder[]|Collections\ArrayCollection
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
     * @ORM\OneToMany(targetEntity="Evaluation\Entity\Evaluation", cascade={"persist"}, mappedBy="country")
     * @Annotation\Exclude()
     *
     * @var Evaluation[]|Collections\ArrayCollection
     */
    private $evaluation;
    /**
     * @ORM\OneToMany(targetEntity="Project\Entity\Rationale", cascade={"persist"}, mappedBy="country")
     * @Annotation\Exclude()
     *
     * @var Rationale[]|Collections\ArrayCollection
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
     * @var Contract[]|Collections\ArrayCollection
     */
    private $contract;
    /**
     * @ORM\ManyToMany(targetEntity="News\Entity\Magazine\Article", cascade={"persist"}, mappedBy="country")
     * @Annotation\Exclude()
     *
     * @var Article[]|Collections\ArrayCollection
     */
    private $magazineArticle;

    public function __construct()
    {
        $this->address = new Collections\ArrayCollection();
        $this->organisation = new Collections\ArrayCollection();
        $this->rationale = new Collections\ArrayCollection();
        $this->vat = new Collections\ArrayCollection();
        $this->funder = new Collections\ArrayCollection();
        $this->evaluation = new Collections\ArrayCollection();
        $this->changeRequestCountry = new Collections\ArrayCollection();
        $this->projectLog = new Collections\ArrayCollection();
        $this->callCountry = new Collections\ArrayCollection();
        $this->contract = new Collections\ArrayCollection();
        $this->magazineArticle = new Collections\ArrayCollection();
    }

    public function __get($property)
    {
        return $this->$property;
    }

    public function __set($property, $value)
    {
        $this->$property = $value;
    }

    public function __isset($property)
    {
        return isset($this->$property);
    }

    public function isItac(): bool
    {
        return null !== $this->itac;
    }

    public function isEu(): bool
    {
        return null !== $this->eu;
    }

    public function isEureka(): bool
    {
        return null !== $this->eureka;
    }

    public function __toString(): string
    {
        return (string)$this->country;
    }

    public function addVat(Collections\Collection $vatCollection): void
    {
        foreach ($vatCollection as $vat) {
            $this->vat->add($vat);
        }
    }

    public function removeVat(Collections\Collection $vatCollection): void
    {
        foreach ($vatCollection as $vat) {
            $this->vat->removeElement($vat);
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id): Country
    {
        $this->id = $id;
        return $this;
    }

    public function getCd()
    {
        return $this->cd;
    }

    public function setCd(?string $cd): Country
    {
        $this->cd = $cd;
        return $this;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry(?string $country): Country
    {
        $this->country = $country;
        return $this;
    }

    public function getDocRef()
    {
        return $this->docRef;
    }

    public function setDocRef(?string $docRef): Country
    {
        $this->docRef = $docRef;
        return $this;
    }

    public function getIso3()
    {
        return $this->iso3;
    }

    public function setIso3(?string $iso3): Country
    {
        $this->iso3 = $iso3;
        return $this;
    }

    public function getNumcode()
    {
        return $this->numcode;
    }

    public function setNumcode($numcode): Country
    {
        $this->numcode = $numcode;
        return $this;
    }

    public function getCountryVat(): ?string
    {
        return $this->countryVat;
    }

    public function setCountryVat(?string $countryVat): Country
    {
        $this->countryVat = $countryVat;
        return $this;
    }

    public function getEu(): ?Eu
    {
        return $this->eu;
    }

    public function setEu(Eu $eu): Country
    {
        $this->eu = $eu;
        return $this;
    }

    public function getEureka()
    {
        return $this->eureka;
    }

    public function setEureka(Eureka $eureka): Country
    {
        $this->eureka = $eureka;
        return $this;
    }

    public function getItac()
    {
        return $this->itac;
    }

    public function setItac(Itac $itac): Country
    {
        $this->itac = $itac;
        return $this;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address): Country
    {
        $this->address = $address;
        return $this;
    }

    public function getOrganisation()
    {
        return $this->organisation;
    }

    public function setOrganisation($organisation): Country
    {
        $this->organisation = $organisation;
        return $this;
    }

    public function getVat()
    {
        return $this->vat;
    }

    public function setVat($vat): Country
    {
        $this->vat = $vat;
        return $this;
    }

    public function getFunder()
    {
        return $this->funder;
    }

    public function setFunder($funder): Country
    {
        $this->funder = $funder;
        return $this;
    }

    public function getFlag()
    {
        return $this->flag;
    }

    public function setFlag(Flag $flag): Country
    {
        $this->flag = $flag;
        return $this;
    }

    public function getEvaluation()
    {
        return $this->evaluation;
    }

    public function setEvaluation($evaluation): Country
    {
        $this->evaluation = $evaluation;
        return $this;
    }

    public function getRationale()
    {
        return $this->rationale;
    }

    public function setRationale($rationale): Country
    {
        $this->rationale = $rationale;
        return $this;
    }

    public function getChangeRequestCountry()
    {
        return $this->changeRequestCountry;
    }

    public function setChangeRequestCountry($changeRequestCountry): Country
    {
        $this->changeRequestCountry = $changeRequestCountry;
        return $this;
    }

    public function getProjectLog()
    {
        return $this->projectLog;
    }

    public function setProjectLog($projectLog): Country
    {
        $this->projectLog = $projectLog;
        return $this;
    }

    public function getCallCountry()
    {
        return $this->callCountry;
    }

    public function setCallCountry($callCountry): Country
    {
        $this->callCountry = $callCountry;
        return $this;
    }

    public function getContract()
    {
        return $this->contract;
    }

    public function setContract($contract): Country
    {
        $this->contract = $contract;
        return $this;
    }

    public function getMagazineArticle()
    {
        return $this->magazineArticle;
    }

    public function setMagazineArticle($magazineArticle): Country
    {
        $this->magazineArticle = $magazineArticle;
        return $this;
    }
}
