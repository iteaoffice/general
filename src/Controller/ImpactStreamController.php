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

use FPDI;
use Project\Entity\Result\Result;
use Project\Search\Service\ImpactStreamSearchService;
use Search\Paginator\Adapter\SolariumPaginator;
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
        $result = $this->getProjectService()->findEntityByDocRef(Result::class, $this->params('docRef'));

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
            if (!is_null($challenge->getPdf())) {
                $fileName = self::parseTempFile('challenge', $challenge->getId());

                file_put_contents($fileName, stream_get_contents($challenge->getPdf()->getPdf()));

                $this->pdf['challenge_' . $challenge->getChallenge()] = $fileName;
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
     * @return FPDI
     */
    protected function generatePdf(): FPDI
    {
        $pdf = new FPDI();

        //Sort the PDF array first
        sort($this->pdf);

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

                // create a page (landscape or portrait depending on the imported page size)
                if ($size['w'] > $size['h']) {
                    $pdf->AddPage('L', [$size['w'], $size['h']]);
                } else {
                    $pdf->AddPage('P', [$size['w'], $size['h']]);
                }

                // use the imported page
                $pdf->useTemplate($templateId);

                $pdf->SetFont('Helvetica');
                $pdf->SetXY(5, 5);
                $pdf->Write(8, $pageNo);
            }
        }

        //Cleanup the tmp folder
        foreach ($this->pdf as $file) {
            unlink($file);
        }

        return $pdf;
    }

    //protected function generateDocumentsByResult(Resu)

    /**
     * @return \Zend\View\Model\ViewModel|string
     */
    public function downloadAction()
    {
        $searchService = $this->getImpactStreamSearchService();

        $data = $this->getRequest()->getQuery()->toArray();
        $searchService->setSearch($data['query'], 'result', 'desc');

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
            $result = $this->getProjectService()->findEntityById(Result::class, $resultId);

            $this->parsePDFsByResult($result);
        }

        //Create the PDF
        $pdf = $this->generatePdf();

        return $pdf->Output();
    }
}
