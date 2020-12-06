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

    public function __construct(
        CollectionFactory $collectionFactory,
        CollectionModifier $collectionModifier,
        Data $catalogData
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->collectionModifier = $collectionModifier;
        $this->catalogData = $catalogData;
    }

    public function getDocuments(): Collection
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $this->collectionModifier->apply($collection);
        $collection->join(['mdpl' => 'opengento_document_product_link'], 'mdpl.document_id=main_table.entity_id', '');
        if ($this->catalogData->getProduct()) {
            $collection->addFieldToFilter('mdpl.product_id', ['eq' => $this->catalogData->getProduct()->getId()]);
        }

        return $collection;
    }
}
