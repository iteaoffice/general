<?php

/**
 * ITEA Office all rights reserved
 *
 * @category  Application
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2019 ITEA Office (https://itea3.org)
 */

declare(strict_types=1);

namespace General\Entity;

use Doctrine\ORM\Mapping as ORM;
use Laminas\Form\Annotation;

/**
 * WebInfo.
 *
 * @ORM\Table(name="password")
 * @ORM\Entity(repositoryClass="General\Repository\Password")
 * @Annotation\Hydrator("Laminas\Hydrator\ObjectPropertyHydrator")
 * @Annotation\Name("password")
 */
class Password extends AbstractEntity
{
    /**
     * @ORM\Column(name="password_id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @var int
     */
    private $id;
    /**
     * @ORM\Column(name="description", type="string", nullable=false)
     * @Annotation\Type("\Laminas\Form\Element\Text")
     * @Annotation\Attributes({"label":"txt-password-description-label","placeholder":"txt-password-description-placeholder"})
     * @Annotation\Options({"help-block":"txt-password-description-help-block"})
     *
     * @var string
     */
    private $description;
    /**
     * @ORM\Column(name="website", type="string", nullable=true)
     * @Annotation\Type("\Laminas\Form\Element\Url")
     * @Annotation\Attributes({"label":"txt-password-website-label","placeholder":"txt-password-website-placeholder"})
     * @Annotation\Options({"help-block":"txt-password-website-help-block"})
     *
     * @var string
     */
    private $website;
    /**
     * @ORM\Column(name="accountname", type="string", nullable=false)
     * @Annotation\Type("\Laminas\Form\Element\Text")
     * @Annotation\Attributes({"label":"txt-password-account-label","placeholder":"txt-password-account-placeholder"})
     * @Annotation\Options({"help-block":"txt-password-account-help-block"})
     *
     * @var string
     */
    private $account;
    /**
     * @ORM\Column(name="username", type="string", nullable=false)
     * @Annotation\Type("\Laminas\Form\Element\Text")
     * @Annotation\Attributes({"label":"txt-password-username-label","placeholder":"txt-password-username-placeholder"})
     * @Annotation\Options({"help-block":"txt-password-username-help-block"})
     *
     * @var string
     */
    private $username;
    /**
     * @ORM\Column(name="password", type="string", nullable=false)
     * @Annotation\Type("\Laminas\Form\Element\Text")
     * @Annotation\Attributes({"label":"txt-password-password-label","placeholder":"txt-password-password-placeholder"})
     * @Annotation\Options({"help-block":"txt-password-password-help-block"})
     *
     * @var string
     */
    private $password;

    public function __toString(): string
    {
        return (string)$this->description;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): Password
    {
        $this->id = $id;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): Password
    {
        $this->description = $description;
        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): Password
    {
        $this->website = $website;
        return $this;
    }

    public function getAccount(): ?string
    {
        return $this->account;
    }

    public function setAccount(?string $account): Password
    {
        $this->account = $account;
        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): Password
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): Password
    {
        $this->password = $password;
        return $this;
    }
}
