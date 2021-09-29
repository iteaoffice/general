<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\Entity\Country;

use Doctrine\ORM\Mapping as ORM;
use General\Entity\AbstractEntity;
use Laminas\Form\Annotation;

/**
 * @ORM\Table(name="country_video")
 * @ORM\Entity
 * @Annotation\Hydrator("Laminas\Hydrator\ObjectPropertyHydrator")
 * @Annotation\Name("country_video")
 */
class Video extends AbstractEntity
{
    public const TYPE_COUNTRY_INFORMATION_SESSION = 1;
    public const TYPE_OTHER                       = 2;

    protected static array $typeTemplates
        = [
            self::TYPE_COUNTRY_INFORMATION_SESSION => 'txt-type-country-information-session',
            self::TYPE_OTHER                       => 'txt-other',
        ];

    /**
     * @ORM\Column(name="country_video_id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private ?int $id = null;
    /**
     * @ORM\Column(name="title", type="text", nullable=false)
     * @Annotation\Type("\Laminas\Form\Element\Text")
     * @Annotation\Options({"label":"txt-country-video-title-label", "help-block":"txt-country-video-title-help-block"})
     * @Annotation\Attributes({"placeholder":"txt-country-video-description-title"})
     */
    private string $title = '';
    /**
     * @ORM\Column(name="type", type="smallint", nullable=false)
     * @Annotation\Type("Laminas\Form\Element\Radio")
     * @Annotation\Attributes({"array":"typeTemplates"})
     * @Annotation\Options({"label":"txt-country-video-type-label","help-block":"txt-country-video-type-help-block"})
     */
    private int $type = self::TYPE_COUNTRY_INFORMATION_SESSION;
    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Annotation\Type("\Laminas\Form\Element\Textarea")
     * @Annotation\Options({"label":"txt-country-video-description-label", "help-block":"txt-country-video-description-help-block"})
     * @Annotation\Attributes({"placeholder":"txt-country-video-description-placeholder"})
     */
    private string $description = '';
    /**
     * @ORM\ManyToOne(targetEntity="General\Entity\Country", inversedBy="videos", cascade={"persist"})
     * @ORM\JoinColumn(name="country_id", referencedColumnName="country_id", nullable=false)
     * @Annotation\Exclude()
     */
    private ?\General\Entity\Country $country = null;
    /**
     * @ORM\ManyToOne(targetEntity="Content\Entity\Video", inversedBy="countryVideo", cascade={"persist"})
     * @ORM\JoinColumn(name="video_id", referencedColumnName="video_id", nullable=false)
     * @Annotation\Type("DoctrineORMModule\Form\Element\EntitySelect")
     * @Annotation\Options({
     *      "help-block":"txt-country-video-video-help-block",
     *      "target_class":"Content\Entity\Video",
     *      "find_method":{
     *          "name":"findBy",
     *          "params": {
     *              "criteria":{},
     *              "orderBy":{
     *                  "id":"DESC"}
     *              }
     *          }
     *      }
     * )
     * @Annotation\Attributes({"label":"txt-country-video-video-label"})
     * @var \Content\Entity\Video
     */
    private ?\Content\Entity\Video $video = null;

    public static function getTypeTemplates(): array
    {
        return self::$typeTemplates;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Video
    {
        $this->id = $id;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Video
    {
        $this->title = $title;
        return $this;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): Video
    {
        $this->type = $type;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): Video
    {
        $this->description = $description;
        return $this;
    }

    public function getCountry(): ?\General\Entity\Country
    {
        return $this->country;
    }

    public function setCountry(\General\Entity\Country $country): Video
    {
        $this->country = $country;
        return $this;
    }

    public function getVideo(): ?\Content\Entity\Video
    {
        return $this->video;
    }

    public function setVideo(\Content\Entity\Video $video): Video
    {
        $this->video = $video;
        return $this;
    }

    public function getTypeText(): string
    {
        return self::$typeTemplates[$this->type] ?? '';
    }
}
