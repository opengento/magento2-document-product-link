<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\DocumentProductLink\Model\Indexer;

use Magento\Framework\Indexer\ActionInterface;
use Opengento\DocumentProductLink\Model\ResourceModel\DocumentProductLink;

class ProductLink implements ActionInterface
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

    public function executeFull(): void
    {
        $this->documentProductLink->deleteIndexerLinks();
        $this->documentProductLink->insertLinks();
    }

    public function executeList(array $ids): void
    {
        $this->documentProductLink->deleteLinksByProducts($ids);
        $this->documentProductLink->insertLinksByProducts($ids);
    }

    public function executeRow($entityId): void
    {
        $this->documentProductLink->deleteLinksByProducts([$entityId]);
        $this->executeList([$entityId]);
    }
}
