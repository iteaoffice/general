<?php

/**
 * ITEA Office all rights reserved
 *
 * @category   General
 *
 * @author     Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright  Copyright (c) 2019 ITEA Office (https://itea3.org)
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
final class ContentTypeIcon extends AbstractViewHelper
{
    /**
     * @var GeneralService
     */
    private $generalService;

    public function __construct(GeneralService $generalService)
    {
        $this->generalService = $generalService;
    }

    public function __invoke(ContentType $contentType = null, string $contentTypeName = null): ?string
    {
        if (null === $contentType && null !== $contentTypeName) {
            $contentType = $this->generalService->findContentTypeByContentTypeName($contentTypeName);
        }

        if (null === $contentType) {
            return null;
        }

        switch (trim($contentType->getContentType())) {
            case 'image/jpeg':
            case 'image/tiff':
            case 'image/png':
                $class = ' fa-file-image-o';
                break;
            case 'application/pdf':
            case 'application/postscript':
                $class = 'fa-file-pdf-o';
                break;
            case 'application/zip':
            case 'application/x-zip-compressed':
                $class = 'fa-file-archive-o';
                break;
            case 'application/vnd.ms-excel':
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
            case 'application/vnd.ms-excel.sheet.macroEnabled.12':
                $class = 'fa-file-excel-o';
                break;
            case 'application/mspowerpoint':
            case 'application/vnd.ms-powerpoint':
            case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
            case 'application/vnd.openxmlformats-officedocument.presentationml.template':
            case 'application/vnd.openxmlformats-officedocument.presentationml.slideshow':
                $class = 'fa-file-powerpoint-o';
                break;
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.template':
            case 'application/msword':
                $class = 'fa-file-word-o';
                break;
            default:
            case 'application/octet-stream':
            case 'application/csv':
            case 'text/xml':
                $class = 'fa-file-o';
                break;
            case 'video/mp4':
                $class = 'fa-file-video-o';
                break;
        }

        return sprintf('<i class="fa %s" title="%s"></i> ', $class, $contentType->getDescription());
    }
}
