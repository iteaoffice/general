<?php

declare(strict_types=1);

namespace General\ValueObject\Link;

use Assert\Assertion;

final class LinkDecoration
{
    public  const TYPE_TEXT          = 'text';
    public  const TYPE_ICON          = 'icon';
    public  const TYPE_ICON_AND_TEXT = 'icon-and-text';
    public  const TYPE_BUTTON        = 'button';
    public  const TYPE_RAW           = 'raw';
    private const ACTION_NEW         = 'new';
    private const ACTION_EDIT        = 'edit';
    private const ACTION_DELETE      = 'delete';

    private static string $iconTemplate = '<i class="fa %s fa-fw"></i>';
    private static string $linkTemplate = '<a href="%%s"%s%s>%s</a>';
    private static array  $defaultIcons = [
        self::ACTION_NEW    => 'fa-plus',
        self::ACTION_EDIT   => 'fa-pencil-square-o',
        self::ACTION_DELETE => 'fa-trash'
    ];

    private string  $type;
    private ?string $text;
    private ?string $title;
    private ?string $action;
    private ?string $icon;

    public function __construct(
        string  $type = self::TYPE_TEXT,
        ?string $text = null,
        ?string $title = null,
        ?string $action = null,
        ?string $icon = null
    )
    {
        Assertion::inArray(
            $type,
            [self::TYPE_TEXT, self::TYPE_BUTTON, self::TYPE_ICON, self::TYPE_ICON_AND_TEXT, self::TYPE_RAW]
        );
        $this->type   = $type;
        $this->text   = $text;
        $this->title  = $title ?? $text;
        $this->action = $action;
        $this->icon   = $icon ?? self::$defaultIcons[(string) $action] ?? null;
    }

    public static function fromArray(array $params): LinkDecoration
    {
        return new self(
            ($params['type'] ?? self::TYPE_TEXT),
            ($params['text'] ?? null),
            ($params['title'] ?? null),
            ($params['action'] ?? null),
            ($params['icon'] ?? null)
        );
    }

    public function parse(): string
    {
        if ($this->type === self::TYPE_RAW) {
            return '%s';
        }

        $content      = [];
        $classes      = [];
        switch ($this->type) {
            case self::TYPE_ICON:
                if ($this->icon !== null) {
                    $content[] = sprintf(self::$iconTemplate, $this->icon);
                }
                break;
            case self::TYPE_ICON_AND_TEXT:
            case self::TYPE_BUTTON:
                if ($this->icon !== null) {
                    $content[] = sprintf(self::$iconTemplate, $this->icon);
                }
                if ($this->text !== null) {
                    $content[] = sprintf(' %s', $this->text);
                }
                if ($this->type === self::TYPE_BUTTON) {
                    $classes = ['btn', 'btn-primary'];
                }
                break;
            case self::TYPE_TEXT:
                if ($this->text !== null) {
                    $content[] = $this->text;
                }
                break;
            default:
                return '%s';
        }

        return sprintf(
            self::$linkTemplate,
            (($this->title === null) ? '' : sprintf(' title="%s"', $this->title)),
            ((empty($classes)) ? '' : sprintf(' class="%s"', implode(' ', $classes))),
            implode($content)
        );
    }
}