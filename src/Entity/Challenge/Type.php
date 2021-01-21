<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\Entity\Challenge;

use Doctrine\Common\Collections;
use Doctrine\ORM\Mapping as ORM;
use General\Entity\AbstractEntity;
use General\Entity\Challenge;
use Laminas\Form\Annotation;

/**
 * @ORM\Table(name="challenge_type")
 * @ORM\Entity(repositoryClass="General\Repository\Challenge\Type")
 * @Annotation\Hydrator("Laminas\Hydrator\ObjectPropertyHydrator")
 * @Annotation\Name("challenge_type")
 */
class Type extends AbstractEntity
{
    public const NOT_ACTIVE_FOR_CALLS = 0;
    public const ACTIVE_FOR_CALLS     = 1;

    protected static array $activeForCallsTemplates
        = [
            self::NOT_ACTIVE_FOR_CALLS => 'txt-not-active-for-calls',
            self::ACTIVE_FOR_CALLS     => 'txt-active-for-calls',
        ];

    /**
     * @ORM\Column(name="type_id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @Annotation\Type("\Laminas\Form\Element\Hidden")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(name="type", type="string", nullable=false)
     * @Annotation\Type("\Laminas\Form\Element\Text")
     * @Annotation\Options({"label":"txt-challenge-type-type-label", "help-block":"txt-challenge-type-type-help-block"})
     * @Annotation\Attributes({"placeholder":"txt-challenge-type-type-placeholder"})
     *
     * @var string
     */
    private $type;
    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Annotation\Type("\Laminas\Form\Element\Textarea")
     * @Annotation\Options({"label":"txt-challenge-type-description-label", "help-block":"txt-challenge-type-description-help-block"})
     * @Annotation\Attributes({"placeholder":"txt-challenge-type-description-placeholder"})
     *
     * @var string
     */
    private $description;
    /**
     * @ORM\Column(name="sequence", type="integer", options={"unsigned":true})
     * @Annotation\Type("\Laminas\Form\Element\Text")
     * @Annotation\Options({"label":"txt-challenge-type-sequence-label", "help-block":"txt-challenge-type-sequence-help-block"})
     * @Annotation\Attributes({"placeholder":"txt-challenge-type-sequence-placeholder"})
     *
     * @var int
     */
    private $sequence;
    /**
     * @ORM\OneToMany(targetEntity="General\Entity\Challenge", cascade={"persist"}, mappedBy="type")
     * @Annotation\Exclude()
     *
     * @var Challenge[]|Collections\ArrayCollection
     */
    private $challenge;
    /**
     * @ORM\Column(name="active_for_calls", type="smallint", nullable=false)
     * @Annotation\Type("Laminas\Form\Element\Radio")
     * @Annotation\Attributes({"array":"activeForCallsTemplates"})
     * @Annotation\Options({"label":"txt-general-challenge-type-active-for-calls-label", "help-block":"txt-challenge-type-active-for-calls-help-block"})
     *
     * @var int
     */
    private $activeForCalls;

    public function __construct()
    {
        $this->challenge      = new Collections\ArrayCollection();
        $this->activeForCalls = self::ACTIVE_FOR_CALLS;
    }

    public static function getActiveForCallsTemplates(): array
    {
        return self::$activeForCallsTemplates;
    }

    public function isActiveForCalls(): bool
    {
        return $this->activeForCalls === self::ACTIVE_FOR_CALLS;
    }

    public function __toString(): string
    {
        return $this->type;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Type
    {
        $this->id = $id;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): Type
    {
        $this->type = $type;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): Type
    {
        $this->description = $description;
        return $this;
    }

    public function getSequence(): ?int
    {
        return $this->sequence;
    }

    public function setSequence(?int $sequence): Type
    {
        $this->sequence = $sequence;
        return $this;
    }

    public function getChallenge()
    {
        return $this->challenge;
    }

    public function setChallenge($challenge): Type
    {
        $this->challenge = $challenge;
        return $this;
    }

    public function getActiveForCalls(): ?int
    {
        return $this->activeForCalls;
    }

    public function setActiveForCalls(?int $activeForCalls): Type
    {
        $this->activeForCalls = $activeForCalls;
        return $this;
    }

    public function getActiveForCallsText(): string
    {
        return self::$activeForCallsTemplates[$this->activeForCalls] ?? '';
    }
}
