<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\DocumentProductLink\ViewModel\Document;

use Magento\Catalog\Model\Config;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Block\ArgumentInterface;

final class ProductLink implements ArgumentInterface
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var Config
     */
    private $catalogConfig;

    /**
     * @var Visibility
     */
    private $productVisibility;

    public function __construct(
        CollectionFactory $collectionFactory,
        Config $catalogConfig,
        Visibility $productVisibility
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->catalogConfig = $catalogConfig;
        $this->productVisibility = $productVisibility;
    }

    /**
     * @inheritdoc
     * @throws LocalizedException
     */
    public function getProducts(array $documentIds): Collection
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addAttributeToSelect($this->catalogConfig->getProductAttributes());
        $collection->addMinimalPrice();
        $collection->addFinalPrice();
        $collection->addTaxPercents();
        $collection->addUrlRewrite();
        $collection->setVisibility($this->productVisibility->getVisibleInCatalogIds());
        $collection->joinTable(['mdpl' => 'opengento_document_product_link'], 'product_id=entity_id', '');
        $collection->getSelect()->where('mdpl.document_id IN (?)', $documentIds);

        return $collection;
    }
}
