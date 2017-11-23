<?php
/**
 * ITEA Office all rights reserved
 *
 * @category  Content
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 * @license   https://itea3.org/license.txt proprietary
 *
 * @link      https://itea3.org
 */

declare(strict_types=1);

namespace General\Controller;

use General\Entity\Challenge\Icon;
use General\Entity\Challenge\Image;
use General\Entity\Country;
use Zend\Http\Response;

/**
 * The index of the system.
 *
 * @category Content
 */
class ImageController extends GeneralAbstractController
{
    /**
     * Index of the Index.
     */
    public function assetAction()
    {
        /** @var Response $response */
        $response = $this->getResponse();


        foreach ($this->getModuleOptions()->getStyleLocations() as $location) {
            $requestedFile = $location .
                DIRECTORY_SEPARATOR .
                $this->getModuleOptions()->getImageLocation() .
                DIRECTORY_SEPARATOR .
                $this->params('name');

            if (file_exists($requestedFile)) {
                $response->getHeaders()
                    ->addHeaderLine('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 36000))
                    ->addHeaderLine("Cache-Control: max-age=36000, must-revalidate")
                    ->addHeaderLine("Pragma: public")
                    ->addHeaderLine('Content-Type: image/png');

                $response->setContent(file_get_contents($requestedFile));

                return $response;
            }
        }

        return $response;
    }

    /**
     * @return Response
     */
    public function flagAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();

        $id = $this->params('id');
        if (\is_null($id)) {
            return $response;
        }
        /** @var Country $country */
        $country = $this->getGeneralService()->findEntityById(Country::class, $id);

        if (\is_null($country)) {
            return $response;
        }

        $response->getHeaders()
            ->addHeaderLine('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 36000))
            ->addHeaderLine("Cache-Control: max-age=36000, must-revalidate")
            ->addHeaderLine("Pragma: public")
            ->addHeaderLine('Content-Type: image/png');

        $response->setContent(stream_get_contents($country->getFlag()->getObject()));

        return $response;
    }

    /**
     * @return Response
     */
    public function challengeIconAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();

        $id = $this->params('id');
        if (\is_null($id)) {
            return $response;
        }
        /** @var Icon $icon */
        $icon = $this->getGeneralService()->findEntityById(Icon::class, $id);

        if (\is_null($icon)) {
            return $response;
        }

        $response->getHeaders()
            ->addHeaderLine('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 36000))
            ->addHeaderLine("Cache-Control: max-age=36000, must-revalidate")
            ->addHeaderLine("Pragma: public")
            ->addHeaderLine('Content-Type: image/png');

        $response->setContent(stream_get_contents($icon->getIcon()));

        return $response;
    }

    /**
     * @return Response
     */
    public function challengeImageAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();

        $id = $this->params('id');
        if (\is_null($id)) {
            return $response;
        }
        /** @var Image $image */
        $image = $this->getGeneralService()->findEntityById(Image::class, $id);

        if (\is_null($image)) {
            return $response;
        }

        $response->getHeaders()
            ->addHeaderLine('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 36000))
            ->addHeaderLine("Cache-Control: max-age=36000, must-revalidate")
            ->addHeaderLine("Pragma: public")
            ->addHeaderLine('Content-Type: image/png');

        $response->setContent(stream_get_contents($image->getImage()));

        return $response;
    }
}
