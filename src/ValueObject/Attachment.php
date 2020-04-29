<?php

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
