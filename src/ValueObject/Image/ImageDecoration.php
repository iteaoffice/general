<?php

declare(strict_types=1);

namespace General\ValueObject\Image;

use Thumbor\Url\Builder;

use function sprintf;

final class ImageDecoration
{
    public const SHOW_IMAGE = 'image';
    public const SHOW_RAW = 'raw';

    private static string $imageTemplate = '<img src="%s" class="img-fluid" alt="%s">';
    private string $show;
    private ?int $width;
    private ?int $height;

    public function __construct(
        string $show = self::SHOW_IMAGE,
        ?int $width = null,
        ?int $height = null
    ) {
        $this->show = $show;
        $this->width = $width;
        $this->height = $height;
    }

    public static function fromArray(array $params): ImageDecoration
    {
        return new self(
            $params['show'] ?? self::SHOW_IMAGE,
            $params['width'] ?? null,
            $params['height'] ?? null
        );
    }

    public function parse(Builder $builder): string
    {
        if (null !== $this->width || null !== $this->height) {
            $builder
                ->resize($this->width, $this->height)
                ->halign('center')
                ->smartCrop(false);
        }

        if ($this->show === self::SHOW_RAW) {
            return (string)$builder;
        }

        return sprintf(
            self::$imageTemplate,
            (string)$builder,
            'Alt'
        );
    }
}
