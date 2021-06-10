<?php

/**
 * ITEA Office all rights reserved
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2021 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 */

declare(strict_types=1);

namespace General\Controller;

use General\Controller\Plugin\GetFilter;
use General\Entity\Challenge;
use General\Entity\Country;
use General\Options\ModuleOptions;
use General\Service\GeneralService;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;

/**
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
        $this->options        = $options;
    }

    public function assetAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();

        $requestedFile = $this->options->getAssets() .
            DIRECTORY_SEPARATOR .
            $this->params('name');

        $contentType = mime_content_type($requestedFile);
        if ($contentType === 'image/svg') {
            $contentType = 'image/svg+xml';
        }

        if (file_exists($requestedFile)) {
            $response->getHeaders()
                ->addHeaderLine('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 36000))
                ->addHeaderLine('Cache-Control: max-age=36000')
                ->addHeaderLine('Content-Type: ' . $contentType)
                ->addHeaderLine('Pragma: public');

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
            ->addHeaderLine('Cache-Control: max-age=36000')
            ->addHeaderLine('Pragma: public')
            ->addHeaderLine('Content-Type: image/png');

        $response->setContent(stream_get_contents($country->getFlag()->getObject()));

        return $response;
    }

    public function challengeIconAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();

        /** @var Challenge\Icon $icon */
        $icon = $this->generalService->find(Challenge\Icon::class, (int)$this->params('id'));

        if (null === $icon) {
            return $response->setStatusCode(Response::STATUS_CODE_404);
        }

        $iconContent = stream_get_contents($icon->getIcon());

        $response->getHeaders()
            ->addHeaderLine('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 36000))
            ->addHeaderLine('Cache-Control: max-age=36000')
            ->addHeaderLine('Pragma: public')
            ->addHeaderLine('etag: "' . sha1($iconContent) . '"')
            ->addHeaderLine('Content-Type: ' . $icon->getContentType()->getContentType());

        $response->setContent($iconContent);

        return $response;
    }

    public function challengeImageAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();

        /** @var Challenge\Image $image */
        $image = $this->generalService->find(Challenge\Image::class, (int)$this->params('id'));

        if (null === $image) {
            return $response->setStatusCode(Response::STATUS_CODE_404);
        }

        $response->getHeaders()
            ->addHeaderLine('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 36000))
            ->addHeaderLine('Cache-Control: max-age=36000')
            ->addHeaderLine('Pragma: public')
            ->addHeaderLine('Content-Type: ' . $image->getContentType()->getContentType());

        $response->setContent(stream_get_contents($image->getImage()));

        return $response;
    }

    public function challengeIdeaPosterIconAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();

        /** @var Challenge\Idea\Poster\Icon $icon */
        $icon = $this->generalService->find(Challenge\Idea\Poster\Icon::class, (int)$this->params('id'));

        if (null === $icon) {
            return $response->setStatusCode(Response::STATUS_CODE_404);
        }

        $response->getHeaders()
            ->addHeaderLine('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 36000))
            ->addHeaderLine('Cache-Control: max-age=36000')
            ->addHeaderLine('Pragma: public')
            ->addHeaderLine('Content-Type: ' . $icon->getContentType()->getContentType());

        $response->setContent(stream_get_contents($icon->getIcon()));

        return $response;
    }

    public function challengeIdeaPosterImageAction(): Response
    {
        /** @var Response $response */
        $response = $this->getResponse();

        /** @var Challenge\Idea\Poster\Image $image */
        $image = $this->generalService->find(Challenge\Idea\Poster\Image::class, (int)$this->params('id'));

        if (null === $image) {
            return $response->setStatusCode(Response::STATUS_CODE_404);
        }

        $response->getHeaders()
            ->addHeaderLine('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 36000))
            ->addHeaderLine('Cache-Control: max-age=36000')
            ->addHeaderLine('Pragma: public')
            ->addHeaderLine('Content-Type: ' . $image->getContentType()->getContentType());

        $response->setContent(stream_get_contents($image->getImage()));

        return $response;
    }
}
