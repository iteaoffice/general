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

use General\Controller\Plugin\GetFilter;
use General\Entity\Challenge\Icon;
use General\Entity\Challenge\Image;
use General\Entity\Country;
use General\Service\GeneralService;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;

/**
 * The index of the system.
 *
 * @category Content
 * @method GetFilter getFilter()
 * @method FlashMessenger flashMessenger()
 */
class ImageController extends AbstractActionController
{
    /**
     * @var GeneralService
     */
    protected $generalService;

    /**
     * ImageController constructor.
     *
     * @param GeneralService $generalService
     */
    public function __construct(GeneralService $generalService)
    {
        $this->generalService = $generalService;
    }


    /**
     * @return Response
     */
    public function flagAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();

        /** @var Country $country */
        $country = $this->generalService->find(Country::class, (int)$this->params('id'));

        if (null === $country) {
            return $response->setStatusCode(Response::STATUS_CODE_404);
        }

        $response->getHeaders()
            ->addHeaderLine('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 36000))
            ->addHeaderLine('Cache-Control: max-age=36000, must-revalidate')
            ->addHeaderLine('Pragma: public')
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

        /** @var Icon $icon */
        $icon = $this->generalService->find(Icon::class, (int)$this->params('id'));

        if (null === $icon) {
            return $response->setStatusCode(Response::STATUS_CODE_404);
        }

        $response->getHeaders()
            ->addHeaderLine('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 36000))
            ->addHeaderLine('Cache-Control: max-age=36000, must-revalidate')
            ->addHeaderLine('Pragma: public')
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

        /** @var Image $image */
        $image = $this->generalService->find(Image::class, (int)$this->params('id'));

        if (null === $image) {
            return $response->setStatusCode(Response::STATUS_CODE_404);
        }

        $response->getHeaders()
            ->addHeaderLine('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 36000))
            ->addHeaderLine('Cache-Control: max-age=36000, must-revalidate')
            ->addHeaderLine('Pragma: public')
            ->addHeaderLine('Content-Type: image/png');

        $response->setContent(stream_get_contents($image->getImage()));

        return $response;
    }
}
