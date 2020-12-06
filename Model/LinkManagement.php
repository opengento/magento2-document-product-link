<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\DocumentProductLink\Model;

use Opengento\DocumentProductLink\Model\ResourceModel\DocumentProductLink;
use Opengento\DocumentProductLink\Api\LinkManagementInterface;

final class LinkManagement implements LinkManagementInterface
{
    /**
     * @var DocumentProductLink
     */
    private $documentProductLink;

    public function __construct(
        DocumentProductLink $documentProductLink
    ) {
        $this->documentProductLink = $documentProductLink;
    }

    public function setLinks(int $documentId, array $productIds): void
    {
        $this->documentProductLink->setDocumentProductLinks($documentId, $productIds);
    }
}
