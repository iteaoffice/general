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

use General\Entity\Challenge;
use General\Service\GeneralService;
use Project\Entity\Result\Result;
use Project\Search\Service\ResultSearchService;
use Project\Service\ResultService;
use Search\Paginator\Adapter\SolariumPaginator;
use setasign\Fpdi\TcpdfFpdi;
use Solarium\QueryType\Select\Query\Query as SolariumQuery;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Paginator\Paginator;

use function count;
use function explode;
use function sprintf;

/**
 * Class ImpactStreamController
 *
 * @package General\Controller
 */
final class ImpactStreamController extends AbstractActionController
{
    private array $challenge = [];
    private array $result = [];
    private ResultService $resultService;
    private GeneralService $generalService;
    private ResultSearchService $resultSearchService;

    public function __construct(
        ResultService $resultService,
        GeneralService $generalService,
        ResultSearchService $resultSearchService
    ) {
        $this->resultService = $resultService;
        $this->generalService = $generalService;
        $this->resultSearchService = $resultSearchService;
    }

    public function downloadSingleAction()
    {
        /** @var Result $result */
        $result = $this->resultService->findResultByDocRef((string)$this->params('docRef'));

        if (null === $result || count($result->getObject()) === 0) {
            return $this->notFoundAction();
        }

        $this->parsePDFsByResult($result);

        //Create the PDF
        $result = $this->generatePdf();

        return $result->Output();
    }

    public function parsePDFsByResult(Result $result): void
    {
        /** @var Challenge $challenge */
        foreach ($this->generalService->parseChallengesByResult($result) as $challenge) {
            if (
                ! array_key_exists(
                    'challenge_' . $challenge->getSequence(),
                    $this->challenge
                )
                && null !== $challenge->getPdf()
            ) {
                $fileName = self::parseTempFile('challenge', $challenge->getId());

                file_put_contents($fileName, stream_get_contents($challenge->getPdf()->getPdf()));

                $this->challenge['challenge_' . $challenge->getSequence()] = $fileName;
            }
        }

        $fileName = self::parseTempFile('result', $result->getId());
        file_put_contents($fileName, stream_get_contents($result->getObject()->first()->getObject()));

        $ordering = sprintf(
            'result_%s_%s',
            ! $result->getProject()->getProjectChallenge()->isEmpty() ? $result->getProject()->getProjectChallenge()
                ->first()->getChallenge()->getSequence() : 1000,
            $result->getResult()
        );

        $this->result[$ordering] = $fileName;
    }

    protected static function parseTempFile(string $entity, int $id): string
    {
        return sys_get_temp_dir() . '/' . $entity . '_' . $id;
    }

    protected function generatePdf(): TcpdfFpdi
    {
        $result = new TcpdfFpdi();

        //Jan Slabon mentioned this great quote:
        //Why ever the author of TCPDF thought it might be a good decision to enable a standard header and footer by default.
        $result->setPrintHeader(false);
        $result->setPrintFooter(false);

        //Sort the PDF arrays first
        ksort($this->challenge);
        ksort($this->result);

        //Add the frontpage
        //Add the references
        $pageCount = $result->setSourceFile(
            __DIR__ . '/../../../../../styles/itea/template/pdf/impact-stream-frontpage.pdf'
        );
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $frontPage = $result->importPage($pageNo);
            $size = $result->getTemplateSize($frontPage);
            $result->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $result->useTemplate($frontPage);
        }

        // iterate through the files
        foreach ($this->challenge as $file) {
            // get the page count
            $pageCount = $result->setSourceFile($file);
            // iterate through all pages
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                // import a page
                $templateId = $result->importPage($pageNo);
                // get the size of the imported page
                $size = $result->getTemplateSize($templateId);

                $result->AddPage($size['orientation'], [$size['width'], $size['height']]);

                // use the imported page
                $result->useTemplate($templateId);
            }
        }


        //Cleanup the tmp folder
        foreach ($this->challenge as $file) {
            unlink($file);
        }

        //Add the references
        $pageCount = $result->setSourceFile(
            __DIR__ . '/../../../../../styles/itea/template/pdf/impact-stream-title-page-stories.pdf'
        );
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $frontPage = $result->importPage($pageNo);
            $size = $result->getTemplateSize($frontPage);
            $result->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $result->useTemplate($frontPage);
        }

        // iterate through the files
        foreach ($this->result as $file) {
            // get the page count
            $pageCount = $result->setSourceFile($file);
            // iterate through all pages
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $result->importPage($pageNo);
                $size = $result->getTemplateSize($templateId);
                $result->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $result->useTemplate($templateId);
            }
        }

        //Cleanup the tmp folder
        foreach ($this->result as $file) {
            unlink($file);
        }

        //Add the references
        $pageCount = $result->setSourceFile(
            __DIR__ . '/../../../../../styles/itea/template/pdf/impact-stream-references.pdf'
        );
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $frontPage = $result->importPage($pageNo);
            $size = $result->getTemplateSize($frontPage);
            $result->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $result->useTemplate($frontPage);
        }

        //Add the references
        $pageCount = $result->setSourceFile(
            __DIR__ . '/../../../../../styles/itea/template/pdf/impact-stream-lastpage.pdf'
        );
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $frontPage = $result->importPage($pageNo);
            $size = $result->getTemplateSize($frontPage);
            $result->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $result->useTemplate($frontPage);
        }

        return $result;
    }

    public function downloadAction(): string
    {
        $data = $this->getRequest()->getQuery()->toArray();

        $searchFields = [
            'result_search',
            'result_abstract',
            'project_search',
            'organisation_search',
            'organisation_type_search',
            'challenge_search',
            'country_search',
            'html'
        ];


        $this->resultSearchService->setSearchImpactStream($data['query'] ?? '', $searchFields, 'result', 'desc');

        if (isset($data['facet'])) {
            foreach ($data['facet'] as $facetField => $values) {
                $quotedValues = [];
                foreach ($values as $value) {
                    $quotedValues[] = sprintf('"%s"', $value);
                }

                $this->resultSearchService->addFilterQuery(
                    $facetField,
                    implode(' ' . SolariumQuery::QUERY_OPERATOR_OR . ' ', $quotedValues)
                );
            }
        }


        $paginator = new Paginator(
            new SolariumPaginator(
                $this->resultSearchService->getSolrClient(),
                $this->resultSearchService->getQuery()
            )
        );
        $paginator::setDefaultItemCountPerPage(2000);
        $paginator->setCurrentPageNumber(1);

        foreach ($paginator->getCurrentItems() as $result) {
            /** @var Result $result */
            $result = $this->resultService->findResultById((int)$result['result_id']);

            $this->parsePDFsByResult($result);
        }

        //Create the PDF
        $result = $this->generatePdf();

        return $result->Output();
    }

    public function downloadSelectedAction()
    {
        $resultIds = explode(',', $this->getRequest()->getQuery('result'));

        if ('' === $this->getRequest()->getQuery('result') || count($resultIds) === 0) {
            return $this->notFoundAction();
        }

        foreach ($resultIds as $resultId) {
            /** @var Result $result */
            $result = $this->resultService->findResultById((int)$resultId);

            $this->parsePDFsByResult($result);
        }

        //Create the PDF
        $result = $this->generatePdf();

        return $result->Output();
    }
}
