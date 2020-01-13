<?php

declare(strict_types=1);

namespace General\ValueObject\Link;

use function mb_strlen;
use function mb_substr;

final class LinkText
{
    public const DEFAULT_MAX_LENGTH = 50;

    private string $text;
    private string $title;
    private int $maxLength = self::DEFAULT_MAX_LENGTH;

    public function __construct(
        ?string $text = null,
        ?string $title = null,
        ?int $maxLength = null
    ) {
        $this->text = $text ?? '';
        $this->title = $title ?? $this->text;
        if (null !== $maxLength) {
            $this->maxLength = $maxLength;
        }
    }

    public static function fromArray(array $params): LinkText
    {
        return new self(
            $params['text'] ?? null,
            $params['title'] ?? null,
            $params['maxLength'] ?? null
        );
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getTitle(): string
    {
        return str_replace('%', '&percnt;', htmlentities($this->title));
    }

    public function parse(): string
    {
        $text = str_replace('%', '&percnt;', htmlentities($this->text));

        return (mb_strlen($text) > $this->maxLength)
            ? trim(mb_substr($text, 0, $this->maxLength)) . '&hellip;'
            : $text;
    }
}
