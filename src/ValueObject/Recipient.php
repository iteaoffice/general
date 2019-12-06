<?php
/**
 *
 */

declare(strict_types=1);

namespace General\ValueObject;

use Zend\Validator\EmailAddress;
use function count;
use function sprintf;

final class Recipient
{
    private string $name;
    private string $email;

    public function __construct(string $name, string $email)
    {
        $this->name = $name;
        $this->email = $email;
    }

    public function isValid(): bool
    {
        return count($this->isInvalidReasons()) === 0;
    }

    public function isInvalidReasons(): array
    {
        $invalidReasons = [];

        $emailValidator = new EmailAddress();

        if (!$emailValidator->isValid($this->email)) {
            $invalidReasons[] = sprintf('Email address (%s) is invalid', $this->email);
        }

        return $invalidReasons;
    }

    public function toArray(): array
    {
        return [
            'Email' => $this->email,
            'Name'  => $this->name,
        ];
    }
}
