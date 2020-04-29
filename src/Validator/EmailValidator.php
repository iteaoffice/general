<?php

/**
 * Jield BV all rights reserved
 *
 * @category  Mailing
 *
 * @author    Johan van der Heide <info@jield.nl>
 * @copyright Copyright (c) 2018 Jield BV (https://jield.nl)
 */

declare(strict_types=1);

namespace General\Validator;

use General\Builder;

/**
 * Class EmailService
 * @package Mailing\Service
 */
class EmailValidator
{
    /**
     * @var Builder\EmailBuilder
     */
    private Builder\EmailBuilder $emailBuilder;
    private bool $isValid = false;
    private array $cannotSendEmailReasons = [];

    public function __construct(Builder\EmailBuilder $emailBuilder)
    {
        $this->emailBuilder = $emailBuilder;

        $this->validate();
    }

    private function validate(): void
    {
        if (count($this->emailBuilder->getTo()) === 0) {
            $this->cannotSendEmailReasons[] = 'No value for $to has been defined';
        }

        if (null === $this->emailBuilder->getSender()) {
            $this->cannotSendEmailReasons[] = 'No sender defined';
        }

        if (null === $this->emailBuilder->getTemplate()) {
            $this->cannotSendEmailReasons[] = 'No template defined';
        }

        if (null === $this->emailBuilder->getSender()) {
            $this->cannotSendEmailReasons[] = 'No sender defined';
        }

        if (null === $this->emailBuilder->getSubject()) {
            $this->cannotSendEmailReasons[] = 'No subject defined';
        }

        if (null === $this->emailBuilder->getHtmlPart()) {
            $this->cannotSendEmailReasons[] = 'No HTML part defined';
        }

        if (null === $this->emailBuilder->getTextPart()) {
            $this->cannotSendEmailReasons[] = 'No Text part defined';
        }

        $this->isValid = (count($this->cannotSendEmailReasons) === 0);
    }

    public function isValid(): bool
    {
        return $this->isValid;
    }

    public function getCannotSendEmailReasons(): ?array
    {
        return $this->cannotSendEmailReasons;
    }
}
