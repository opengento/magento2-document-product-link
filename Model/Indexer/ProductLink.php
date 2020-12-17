<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\DocumentProductLink\Model\Indexer;

use Magento\Catalog\Model\Product;
use Magento\Framework\Indexer\ActionInterface;
use Magento\Framework\Indexer\CacheContext;
use Opengento\Document\Model\Document;
use Opengento\DocumentProductLink\Model\ResourceModel\DocumentProductLink;

class ProductLink implements ActionInterface
{
    /**
     * @var DocumentProductLink
     */
    private $documentProductLink;

    /**
     * @var CacheContext
     */
    private $cacheContext;

    public function __construct(
        DocumentProductLink $documentProductLink,
        CacheContext $cacheContext
    ) {
        $this->documentProductLink = $documentProductLink;
        $this->cacheContext = $cacheContext;
    }

    public function executeFull(): void
    {
        $this->documentProductLink->deleteIndexerLinks();
        $this->documentProductLink->insertLinks();

        $this->cacheContext->registerTags([Document::CACHE_TAG, Product::CACHE_TAG]);
    }

    public function executeList(array $ids): void
    {
        $this->documentProductLink->deleteLinksByProducts($ids);
        $this->documentProductLink->insertLinksByProducts($ids);

        $this->cacheContext->registerEntities(Product::CACHE_TAG, $ids);
        $this->cacheContext->registerTags([Document::CACHE_TAG]);
    }

    public function executeRow($entityId): void
    {
        $this->executeList([$entityId]);
    }
}
