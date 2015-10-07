<?php

/**
 * ITEA Office copyright message placeholder.
 *
 * @category   General
 *
 * @author     Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright  Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
 */

namespace General\View\Helper;

use General\Entity\ContentType;

/**
 * Create a link to an project.
 *
 * @category   General
 */
class ContentTypeIcon extends HelperAbstract
{
    /**
     * @param ContentType $contentType
     *
     * @return string
     */
    public function __invoke(ContentType $contentType)
    {
        switch (trim($contentType->getContentType())) {
            case 'image/jpeg':
            case 'image/tiff':
            case 'image/png':
                $class = " fa-file-image-o";
                break;
            case 'application/pdf':
            case 'application/postscript':
                $class = "fa-file-pdf-o";
                break;
            case 'application/zip':
            case 'application/x-zip-compressed':
                $class = "fa-file-archive-o";
                break;
            case 'application/vnd.ms-excel':
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
            case 'application/vnd.ms-excel.sheet.macroEnabled.12':
                $class = "fa-file-excel-o";
                break;
            case 'application/mspowerpoint':
            case 'application/vnd.ms-powerpoint':
            case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
            case 'application/vnd.openxmlformats-officedocument.presentationml.template':
                $class = 'fa-file-powerpoint-o';
                break;
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.template':
            case 'application/msword':
                $class = "fa-file-word-o";
                break;
            case 'application/octet-stream':
            case 'application/csv':
            case 'text/xml':
                $class = "fa-file-o";
                break;
            case 'video/mp4':
                $class = "fa-file-video-o";
                break;
            default:
                return sprintf('%s not found', $contentType->getContentType());
        }

        return sprintf('<i class="fa %s" title="%s"></i> ', $class, $contentType->getDescription());
    }
}
