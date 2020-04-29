<?php

/**
 * Jield BV all rights reserved
 *
 * @category    Application
 *
 * @author      Dr. ir. Johan van der Heide <info@jield.nl>
 * @copyright   Copyright (c)  2019 Jield BV (https://jield.nl)
 */

declare(strict_types=1);

namespace General\Options;

use Laminas\Stdlib\AbstractOptions;

class EmailOptions extends AbstractOptions
{
    private bool $active = true;
    private bool $development = false;
    private string $emailAddress = 'webmaster@itea3.org';
    private string $username = 'username';
    private string $password = 'password';

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): EmailOptions
    {
        $this->active = $active;

        return $this;
    }

    public function isDevelopment(): bool
    {
        return $this->development;
    }

    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    public function setEmailAddress(string $emailAddress): EmailOptions
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    public function setDevelopment(bool $development): EmailOptions
    {
        $this->development = $development;
        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): EmailOptions
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): EmailOptions
    {
        $this->password = $password;
        return $this;
    }
}
