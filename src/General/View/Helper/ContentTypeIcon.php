<?php

/**
 * ITEA Office copyright message placeholder
 *
 * @category    General
 * @package     View
 * @subpackage  Helper
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2014 ITEA Office (http://itea3.org)
 */
namespace General\View\Helper;

use General\Entity\ContentType;

/**
 * Create a link to an project
 *
 * @category    General
 * @package     View
 * @subpackage  Helper
 */
class ContentTypeIcon extends HelperAbstract
{
    /**
     * @param  ContentType $contentType
     * @return string
     */
    public function __invoke(ContentType $contentType)
    {
        switch (trim($contentType->getContentType())) {
            case 'application/pdf':
                $class = "fa-file-pdf-o";
                break;
            case 'application/zip':
                $class = "fa-file-archive-o";
                break;
            case 'application/msword':
            case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                $class = "fa-file-excel-o";
                break;
            case 'application/mspowerpoint':
            case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
                $class = 'fa-file-powerpoint-o';
                break;
            case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                $class = "fa-file-word-o";
                break;
            case 'application/octet-stream':
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
