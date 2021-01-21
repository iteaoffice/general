<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\View\Helper;

use General\Entity\ContentType;
use General\Service\GeneralService;

use function sprintf;

/**
 * Class ContentTypeIcon
 *
 * @package General\View\Helper
 */
final class ContentTypeIcon
{
    private GeneralService $generalService;

    public function __construct(GeneralService $generalService)
    {
        $this->generalService = $generalService;
    }

    public function __invoke(ContentType $contentType = null, string $contentTypeName = null): ?string
    {
        if (null === $contentType && null !== $contentTypeName) {
            $contentType = $this->generalService->findContentTypeByContentTypeDescription($contentTypeName);
        }

        if (null === $contentType) {
            return null;
        }

        switch (trim($contentType->getContentType())) {
            case 'image/jpeg':
            case 'image/tiff':
            case 'image/png':
                $class = 'far fa-file-image';
                break;
            case 'application/pdf':
            case 'application/postscript':
                $class = 'far fa-file-pdf';
                break;
            case 'application/zip':
            case 'application/x-zip-compressed':
                $class = 'far fa-file-archive';
                break;
            case 'application/vnd.ms-excel':
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
            case 'application/vnd.ms-excel.sheet.macroEnabled.12':
                $class = 'far fa-file-excel';
                break;
            case 'application/mspowerpoint':
            case 'application/vnd.ms-powerpoint':
            case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
            case 'application/vnd.openxmlformats-officedocument.presentationml.template':
            case 'application/vnd.openxmlformats-officedocument.presentationml.slideshow':
                $class = 'far fa-file-powerpoint';
                break;
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.template':
            case 'application/msword':
                $class = 'far fa-file-word';
                break;
            default:
            case 'application/octet-stream':
            case 'application/csv':
            case 'text/xml':
                $class = 'far fa-file';
                break;
            case 'video/mp4':
                $class = 'far fa-file-video';
                break;
        }

        return sprintf('<i class="%s" title="%s"></i> ', $class, $contentType->getDescription());
    }
}
