<?php

/**
 * Jield BV all rights reserved
 *
 * @category  Mailing
 *
 * @author    Johan van der Heide <info@jield.nl>
 * @copyright Copyright (c) 2018 Jield BV (https://jield.nl)
 */

declare(strict_types=1);

namespace General\Builder;

use Contact\Service\ContactService;
use Deeplink\Service\DeeplinkService;
use General\Entity\WebInfo;
use General\Options\EmailOptions;
use General\Service\GeneralService;
use InvalidArgumentException;
use Mailing\Service\MailingService;

/**
 * Class TransactionalEmailBuilder
 * @package General\Builder
 */
final class WebInfoEmailBuilder extends EmailBuilder
{
    private WebInfo $webInfo;

    public function __construct(
        string $webInfoKey,
        EmailOptions $emailOptions,
        GeneralService $generalService,
        MailingService $mailingService,
        ContactService $contactService,
        DeeplinkService $deeplinkService
    ) {
        parent::__construct($emailOptions, $mailingService, $contactService, $deeplinkService);

        $webInfo = $generalService->findWebInfoByInfo($webInfoKey);

        if (null === $webInfo) {
            throw new InvalidArgumentException(sprintf('Web Info email with key "%s" cannot be found', $webInfoKey));
        }

        $this->setSender($webInfo->getSender());
        $this->webInfo = $webInfo;
    }

    public function renderEmail(): void
    {
        $this->emailCampaign = (string)$this->webInfo->getInfo();
        $this->template      = $this->webInfo->getTemplate();

        $this->renderSubject($this->webInfo->getSubject());
        $this->renderBody($this->webInfo->getContent());
    }
}
