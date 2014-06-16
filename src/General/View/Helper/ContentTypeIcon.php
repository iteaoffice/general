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
     * @param ContentType $contentType
     * @param int         $width
     *
     * @return string
     */
    public function __invoke(ContentType $contentType, $width = 20)
    {

        $image = $contentType->getImage();

        if (is_null($image)) {
            return '';
        }

        /**
         * Check if the file is cached and if so, pull it from the assets-folder
         */
        $router = 'content-type/icon';
        if (file_exists($contentType->getCacheFileName())) {
            /**
             * The file exists, but is it not updated?
             */
            $router = 'assets/content-type-icon';
        } else {
            file_put_contents(
                $contentType->getCacheFileName(),
                (is_resource($contentType->getImage()) ?
                    stream_get_contents($contentType->getImage()) : $contentType->getImage())
            );
        }

        $imageUrl = '<img src="%s" id="%s" width="%s">';

        $params = [
            'hash' => $contentType->getHash(),
            'id'   => $contentType->getId()
        ];

        $image = sprintf(
            $imageUrl,
            $this->getUrl($router, $params),
            'content_type_icon_' . $contentType->getExtension(),
            $width
        );

        return $image;
    }
}
