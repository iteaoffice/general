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
    protected $challenge = [];
    /**
     * @var array
     */
    protected $result = [];

    /**
     * @return \Zend\View\Model\ViewModel|string
     */
    public function downloadSingleAction()
    {
        /** @var Result $result */
        $result = $this->getProjectService()->findEntityByDocRef(Result::class, (string)$this->params('docRef'));

        if (\is_null($result) || count($result->getObject()) === 0) {
            return $this->notFoundAction();
        }

        $this->parsePDFsByResult($result);

        //Create the PDF
        $result = $this->generatePdf();

        return $result->Output();
    }

    /**
     * @param Result $result
     */
    public function parsePDFsByResult(Result $result): void
    {
        foreach ($this->getGeneralService()->parseChallengesByResult($result) as $challenge) {
            if (!array_key_exists(
                'challenge_' . $challenge->getSequence(),
                $this->challenge
            ) && !\is_null($challenge->getPdf())) {
                $fileName = self::parseTempFile('challenge', $challenge->getId());

                file_put_contents($fileName, stream_get_contents($challenge->getPdf()->getPdf()));

                $this->challenge['challenge_' . $challenge->getSequence()] = $fileName;
            }
        }

        $fileName = self::parseTempFile('result', $result->getId());
        file_put_contents($fileName, stream_get_contents($result->getObject()->first()->getObject()));
        $this->result['result' . $result->getResult()] = $fileName;
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
        $result = new TcpdfFpdi();

        //Jan Slabon mentioned this great quote:
        //Why ever the author of TCPDF thought it might be a good decision to enable a standard header and footer by default.
        $result->setPrintHeader(false);
        $result->setPrintFooter(false);

        //Sort the PDF arrays first
        sort($this->challenge);
        sort($this->result);

        //Add the frontpage
        //Add the references
        $pageCount = $result->setSourceFile(__DIR__ . '/../../../../../styles/itea/template/pdf/impact-stream-frontpage.pdf');
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
        $pageCount = $result->setSourceFile(__DIR__ . '/../../../../../styles/itea/template/pdf/impact-stream-title-page-stories.pdf');
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
        $pageCount = $result->setSourceFile(__DIR__ . '/../../../../../styles/itea/template/pdf/impact-stream-references.pdf');
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $frontPage = $result->importPage($pageNo);
            $size = $result->getTemplateSize($frontPage);
            $result->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $result->useTemplate($frontPage);
        }

        //Add the references
        $pageCount = $result->setSourceFile(__DIR__ . '/../../../../../styles/itea/template/pdf/impact-stream-lastpage.pdf');
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $frontPage = $result->importPage($pageNo);
            $size = $result->getTemplateSize($frontPage);
            $result->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $result->useTemplate($frontPage);
        }

        return $result;
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
        $result = $this->generatePdf();

        return $result->Output();
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

        if (\count($resultIds) === 0) {
            return $this->notFoundAction();
        }

        foreach ($resultIds as $resultId) {
            /** @var Result $result */
            $result = $this->getProjectService()->findEntityById(Result::class, $resultId);

            $this->parsePDFsByResult($result);
        }

        //Create the PDF
        $result = $this->generatePdf();

        return $result->Output();
    }
}
