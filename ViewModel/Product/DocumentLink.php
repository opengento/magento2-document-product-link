<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\DocumentProductLink\ViewModel\Product;

use Magento\Catalog\Helper\Data;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Opengento\Document\Model\ResourceModel\Document\Collection;
use Opengento\Document\Model\ResourceModel\Document\CollectionFactory;
use Opengento\DocumentProductLink\Model\Document\Collection\CollectionModifier;

final class DocumentLink implements ArgumentInterface
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var CollectionModifier
     */
    private $collectionModifier;

    /**
     * @var Data
     */
    private $catalogData;

    /**
     * @var Collection[]
     */
    private $collection;

    public function __construct(
        CollectionFactory $collectionFactory,
        CollectionModifier $collectionModifier,
        Data $catalogData
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->collectionModifier = $collectionModifier;
        $this->catalogData = $catalogData;
        $this->collection = [];
    }

    public function getDocuments(?int $productId = null): Collection
    {
        if ($productId === null) {
            $productId = $this->catalogData->getProduct() ? (int) $this->catalogData->getProduct()->getId() : -1;
        }

        return $this->collection[$productId] ?? $this->collection[$productId] = $this->loadCollection($productId);
    }

    private function loadCollection(int $productId): Collection
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $this->collectionModifier->apply($collection);
        $collection->join(['odpl' => 'opengento_document_product_link'], 'odpl.document_id=main_table.entity_id', '');
        $collection->addFieldToFilter('odpl.product_id', ['eq' => $productId]);

        return $collection;
    }
}
