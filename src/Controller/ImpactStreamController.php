<?php
/**
 * ITEA Office all rights reserved
 *
 * PHP Version 7
 *
 * @category    Project
 *
 * @author      Johan van der Heide <johan.van.der.heide@itea3.org>
 * @copyright   Copyright (c) 2004-2017 ITEA Office (https://itea3.org)
 * @license     https://itea3.org/license.txt proprietary
 *
 * @link        https://github.com/iteaoffice/general for the canonical source repository
 */

declare(strict_types=1);

namespace General\Controller;

use Project\Entity\Result\Result;
use Project\Search\Service\ImpactStreamSearchService;
use Search\Paginator\Adapter\SolariumPaginator;
use setasign\Fpdi\TcpdfFpdi;
use Solarium\QueryType\Select\Query\Query as SolariumQuery;
use Zend\Paginator\Paginator;

/**
 *
 */
class ImpactStreamController extends GeneralAbstractController
{
    /**
     * @var array
     */
    protected $pdf = [];

    /**
     * @return \Zend\View\Model\ViewModel|string
     */
    public function downloadSingleAction()
    {
        /** @var Result $result */
        $result = $this->getProjectService()->findEntityByDocRef(Result::class, (string)$this->params('docRef'));

        if (is_null($result) || count($result->getObject()) === 0) {
            return $this->notFoundAction();
        }

        $this->parsePDFsByResult($result);

        //Create the PDF
        $pdf = $this->generatePdf();

        return $pdf->Output();
    }

    /**
     * @param Result $result
     */
    public function parsePDFsByResult(Result $result): void
    {
        foreach ($this->getGeneralService()->parseChallengesByResult($result) as $challenge) {
            if (!array_key_exists('challenge_' . $challenge->getSequence(), $this->pdf) && !is_null($challenge->getPdf())) {
                $fileName = self::parseTempFile('challenge', $challenge->getId());

                file_put_contents($fileName, stream_get_contents($challenge->getPdf()->getPdf()));

                $this->pdf['challenge_' . $challenge->getSequence()] = $fileName;
            }
        }

        $fileName = self::parseTempFile('result', $result->getId());
        file_put_contents($fileName, stream_get_contents($result->getObject()->first()->getObject()));
        $this->pdf['result' . $result->getResult()] = $fileName;
    }

    /**
     * @param string $entity
     * @param int $id
     * @return string
     */
    protected static function parseTempFile(string $entity, int $id): string
    {
        return sys_get_temp_dir() . '/' . $entity . '_' . $id;
    }

    /**
     * @return TcpdfFpdi
     */
    protected function generatePdf(): TcpdfFpdi
    {
        $pdf = new TcpdfFpdi();

        //Sort the PDF array first
        sort($this->pdf);

        $counter = 1;

        //Add the frontpage
        $pdf->setSourceFile(__DIR__ . '/../../../../../styles/itea/template/pdf/impact-stream-frontpage.pdf');
        $frontPage = $pdf->importPage(1);
        $size = $pdf->getTemplateSize($frontPage);
        $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
        $pdf->useTemplate($frontPage);

        // iterate through the files
        foreach ($this->pdf as $file) {
            // get the page count
            $pageCount = $pdf->setSourceFile($file);
            // iterate through all pages
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                // import a page
                $templateId = $pdf->importPage($pageNo);
                // get the size of the imported page
                $size = $pdf->getTemplateSize($templateId);

                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);

                // use the imported page
                $pdf->useTemplate($templateId);

                //
                //
//                $pdf->SetFont('Helvetica');
//                $pdf->SetXY(5, 5);
//                $pdf->Write(8, $counter);

                $counter++;
            }
        }

        //Cleanup the tmp folder
        foreach ($this->pdf as $file) {
            unlink($file);
        }

        //Add the lastpage
        $pdf->setSourceFile(__DIR__ . '/../../../../../styles/itea/template/pdf/impact-stream-lastpage.pdf');
        $frontPage = $pdf->importPage(1);
        $size = $pdf->getTemplateSize($frontPage);
        $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
        $pdf->useTemplate($frontPage);

        return $pdf;
    }

    /**
     * @return \Zend\View\Model\ViewModel|string
     */
    public function downloadAction()
    {
        $searchService = $this->getImpactStreamSearchService();

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


        $searchService->setSearch($data['query'], $searchFields, 'result', 'desc');

        if (isset($data['facet'])) {
            foreach ($data['facet'] as $facetField => $values) {
                $quotedValues = [];
                foreach ($values as $value) {
                    $quotedValues[] = sprintf("\"%s\"", $value);
                }

                $searchService->addFilterQuery(
                    $facetField,
                    implode(' ' . SolariumQuery::QUERY_OPERATOR_OR . ' ', $quotedValues)
                );
            }
        }


        $paginator = new Paginator(new SolariumPaginator($searchService->getSolrClient(), $searchService->getQuery()));
        $paginator::setDefaultItemCountPerPage(2000);
        $paginator->setCurrentPageNumber(1);

        foreach ($paginator->getCurrentItems() as $result) {
            /** @var Result $result */
            $result = $this->getProjectService()->findEntityById(Result::class, $result['result_id']);

            $this->parsePDFsByResult($result);
        }

        //Create the PDF
        $pdf = $this->generatePdf();

        return $pdf->Output();
    }

    /**
     * @return ImpactStreamSearchService
     */
    public function getImpactStreamSearchService(): ImpactStreamSearchService
    {
        return $this->getProjectService()->getServiceLocator()->get(ImpactStreamSearchService::class);
    }

    /**
     * @return \Zend\View\Model\ViewModel|string
     */
    public function downloadSelectedAction()
    {
        $resultIds = explode(',', $this->getRequest()->getQuery('result'));

        if (count($resultIds) === 0) {
            return $this->notFoundAction();
        }

        foreach ($resultIds as $resultId) {
            /** @var Result $result */
            $result = $this->getProjectService()->findEntityById(Result::class, $resultId);

            $this->parsePDFsByResult($result);
        }

        //Create the PDF
        $pdf = $this->generatePdf();

        return $pdf->Output();
    }
}
