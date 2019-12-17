<?php

declare(strict_types=1);

namespace General\ValueObject\Link;

use function implode;
use function sprintf;

final class LinkDecoration
{
    public const SHOW_TEXT = 'text';
    public const SHOW_ICON = 'icon';
    public const SHOW_ICON_AND_TEXT = 'icon-and-text';
    public const SHOW_BUTTON = 'button';
    public const SHOW_DANGER_BUTTON = 'danger-button';
    public const SHOW_RAW = 'raw';
    public const SHOW_SOCIAL = 'social'; //Legacy constant;

    private const ACTION_NEW = 'new';
    private const ACTION_EDIT = 'edit';
    private const ACTION_DELETE = 'delete';

    private static string $iconTemplate = '<i class="fa %s fa-fw"></i>';
    private static string $linkTemplate = '<a href="%%s"%s%s>%s</a>';
    private static array  $defaultIcons = [
        self::ACTION_NEW => 'fa-plus',
        self::ACTION_EDIT => 'fa-pencil-square-o',
        self::ACTION_DELETE => 'fa-trash'
    ];

    private string   $show;
    private LinkText $linkText;
    private ? string  $icon;

    public function __construct(
        string $show = self::SHOW_TEXT,
        ?LinkText $linkText = null,
        ?string $action = null,
        ?string $icon = null
    ) {
        $this->show = $show;
        $this->linkText = $linkText ?? new LinkText();
        $this->icon = $icon ?? self::$defaultIcons[(string)$action] ?? null;
    }

    public static function fromArray(array $params) : LinkDecoration
    {
        return new self(
            ($params['show'] ?? self::SHOW_TEXT),
            LinkText::fromArray($params),
            ($params['action'] ?? null),
            ($params['icon'] ?? null)
        );
    }

    public function parse(): string
    {
        if ($this->show === self::SHOW_RAW) {
            return '%s';
        }

        /** @todo legacy statement we keep it as long as we have too many 'social' links */
        if ($this->show === self::SHOW_SOCIAL) {
            return '%s';
        }

        $content = [];
        $classes = [];
        switch ($this->show) {
            case self::SHOW_ICON:
                if ($this->icon !== null) {
                    $content[] = sprintf(self::$iconTemplate, $this->icon);
                }
                break;
            case self::SHOW_ICON_AND_TEXT:
            case self::SHOW_BUTTON:
            case self::SHOW_DANGER_BUTTON:
                if ($this->icon !== null) {
                    $content[] = sprintf(self::$iconTemplate, $this->icon);
                }
                $text = $this->linkText->parse();
                if (!empty($text)) {
                    $content[] = sprintf(' %s', $text);
                }
                if ($this->show === self::SHOW_BUTTON) {
                    $classes = ['btn', 'btn-primary'];
                }
                if ($this->show === self::SHOW_DANGER_BUTTON) {
                    $classes = ['btn', 'btn-danger'];
                }
                break;
            case self::SHOW_TEXT:
            default:
                $content[] = $this->linkText->parse();
                break;
        }

        return sprintf(
            self::$linkTemplate,
            ((empty($this->linkText->getTitle())) ? '' : sprintf(' title="%s"', $this->linkText->getTitle())),
            ((empty($classes)) ? '' : sprintf(' class="%s"', implode(' ', $classes))),
            implode($content)
        );
    }
}
