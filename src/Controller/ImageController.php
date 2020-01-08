<?php

/**
 * ITEA Office all rights reserved
 *
 * @category  Content
 *
 * @author    Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright Copyright (c) 2019 ITEA Office (https://itea3.org)
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
use General\Options\ModuleOptions;
use General\Service\GeneralService;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;

/**
 * The index of the system.
 *
 * @category Content
 * @method GetFilter getFilter()
 * @method FlashMessenger flashMessenger()
 */
final class ImageController extends AbstractActionController
{
    private GeneralService $generalService;
    private ModuleOptions $options;

    public function __construct(GeneralService $generalService, ModuleOptions $options)
    {
        $this->generalService = $generalService;
        $this->options = $options;
    }

    public function assetAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();

        $requestedFile = $this->options->getAssets() .
            DIRECTORY_SEPARATOR .
            $this->params('name');

        if (file_exists($requestedFile)) {
            $response->getHeaders()
                ->addHeaderLine('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 36000))
                ->addHeaderLine('Cache-Control: max-age=36000, must-revalidate')
                ->addHeaderLine('Pragma: public')
                ->addHeaderLine('Content-Type: image/png');

            $response->setContent(file_get_contents($requestedFile));

            return $response;
        }

        return $response;
    }

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
