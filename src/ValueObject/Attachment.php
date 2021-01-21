<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

/**
 *
 */

declare(strict_types=1);

namespace General\ValueObject;

final class Attachment
{
    private string $contentType;
    private string $fileName;
    private string $base64Content;
    private ?string $contentId;

    public function __construct(string $contentType, string $fileName, string $base64Content, ?string $contentId = null)
    {
        $this->contentType = $contentType;
        $this->fileName = $fileName;
        $this->base64Content = $base64Content;
        $this->contentId = $contentId;
    }

    public function toArray(): array
    {
        $return = [
            'ContentType'   => $this->contentType,
            'Filename'      => $this->fileName,
            'Base64Content' => $this->base64Content
        ];


        if (null !== $this->contentId) {
            $return ['ContentId'] = $this->contentId;
        }
        return $return;
    }
}
