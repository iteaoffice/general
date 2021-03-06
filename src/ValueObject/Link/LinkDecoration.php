<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\ValueObject\Link;

use function implode;
use function sprintf;

final class LinkDecoration
{
    public const SHOW_TEXT          = 'text';
    public const SHOW_ICON          = 'icon';
    public const SHOW_ICON_AND_TEXT = 'icon-and-text';
    public const SHOW_BUTTON        = 'button';
    public const SHOW_BUTTON_SMALL  = 'button-small';
    public const SHOW_DANGER_BUTTON = 'danger-button';
    public const SHOW_HELP_BUTTON   = 'help-button';
    public const SHOW_RAW           = 'raw';

    private const ACTION_NEW    = 'new';
    private const ACTION_EDIT   = 'edit';
    private const ACTION_DELETE = 'delete';

    private static string $iconTemplate = '<i class="%s"></i>';
    private static string $linkTemplate = '<a href="%%s"%s%s>%s</a>';
    private static array $defaultIcons = [
        self::ACTION_NEW    => 'fas fa-plus',
        self::ACTION_EDIT   => 'far fa-edit',
        self::ACTION_DELETE => 'far fa-trash-alt'
    ];

    private string $show;
    private LinkText $linkText;
    private ?string $icon;

    public function __construct(
        string $show = self::SHOW_TEXT,
        ?LinkText $linkText = null,
        ?string $action = null,
        ?string $icon = null
    ) {
        $this->show     = $show;
        $this->linkText = $linkText ?? new LinkText();
        $this->icon     = $icon ?? self::$defaultIcons[(string)$action] ?? null;
    }

    public static function fromArray(array $params): LinkDecoration
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
            case self::SHOW_BUTTON_SMALL:
            case self::SHOW_DANGER_BUTTON:
            case self::SHOW_HELP_BUTTON:
                if ($this->icon !== null) {
                    $content[] = sprintf(self::$iconTemplate, $this->icon);
                }
                $text = $this->linkText->parse();
                if (! empty($text)) {
                    $content[] = sprintf(' %s', $text);
                }
                if ($this->show === self::SHOW_BUTTON) {
                    $classes = ['btn', 'btn-primary'];
                }
                if ($this->show === self::SHOW_DANGER_BUTTON) {
                    $classes = ['btn', 'btn-danger'];
                }
                if ($this->show === self::SHOW_HELP_BUTTON) {
                    $classes = ['btn', 'btn-info'];
                }

                if ($this->show === self::SHOW_BUTTON_SMALL) {
                    $classes = ['btn', 'btn-primary', 'btn-sm'];
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
