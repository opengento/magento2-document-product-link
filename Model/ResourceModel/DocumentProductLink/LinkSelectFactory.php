<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\DocumentProductLink\Model\ResourceModel\DocumentProductLink;

use Generator;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\DB\Select;
use Magento\Framework\Exception\LocalizedException;
use Opengento\Document\Model\ResourceModel\DocumentType\Collection as DocumentTypeCollection;
use Opengento\Document\Model\ResourceModel\DocumentType\CollectionFactory as DocumentTypeCollectionFactory;
use Opengento\DocumentProductLink\Model\Config\LinkProvider;
use Zend_Db_Select_Exception;
use function array_reduce;

final class LinkSelectFactory
{
    /**
     * @var ProductCollectionFactory
     */
    private $pCollectionFactory;

    /**
     * @var DocumentTypeCollectionFactory
     */
    private $dtCollectionFactory;

    /**
     * @var LinkProvider
     */
    private $linkProvider;

    public function __construct(
        ProductCollectionFactory $pCollectionFactory,
        DocumentTypeCollectionFactory $dtCollectionFactory,
        LinkProvider $linkProvider
    ) {
        $this->pCollectionFactory = $pCollectionFactory;
        $this->dtCollectionFactory = $dtCollectionFactory;
        $this->linkProvider = $linkProvider;
    }

    /**
     * @inheritdoc
     * @throws LocalizedException
     * @throws Zend_Db_Select_Exception
     */
    public function createSelects(): ?Generator
    {
        foreach ($this->linkProvider->getPivotFields() as $pivotField => $documentTypeIds) {
            yield $this->createSelect($documentTypeIds, $pivotField);
        }
    }

    /**
     * @inheritdoc
     * @throws LocalizedException
     * @throws Zend_Db_Select_Exception
     */
    public function createSelectsByDocuments(array $documentIds): ?Generator
    {
        if (!$documentIds) {
            yield from $this->createSelects();

            return;
        }

        /** @var DocumentTypeCollection $collection */
        $collection = $this->dtCollectionFactory->create();
        $collection->join(['d' => 'opengento_document'], 'main_table.entity_id=d.type_id', '');
        $collection->addFieldToFilter('d.entity_id', ['in' => $documentIds]);
        $collection->distinct(true);

        $pivotFields = array_reduce(
            $collection->getAllIds(),
            function (array $pivotFields, $documentTypeId): array {
                $pivotFields[$this->linkProvider->getPivotField((int) $documentTypeId)][] = (int) $documentTypeId;

                return $pivotFields;
            },
            []
        );

        foreach ($pivotFields as $pivotField => $documentTypeIds) {
            $select = $this->createSelect($documentTypeIds, $pivotField);
            $select->where('d.entity_id IN (?)', $documentIds);

            yield $select;
        }
    }

    /**
     * @inheritdoc
     * @throws LocalizedException
     * @throws Zend_Db_Select_Exception
     */
    private function createSelect(array $documentTypeIds, string $pivotField): Select
    {
        /** @var ProductCollection $collection */
        $collection = $this->pCollectionFactory->create();
        $collection->addAttributeToFilter($pivotField, ['notnull' => true]);
        $collection->addStoreFilter();
        $collection->joinTable(['dp' => 'opengento_document_pivot'], 'identifier=' . $pivotField, ['document_id']);
        $collection->getSelect()->joinInner(['d' => 'opengento_document'], 'd.entity_id=dp.document_id', '');

        return $collection->getSelect()->where('d.type_id IN (?)', $documentTypeIds)->setPart(
            Select::COLUMNS,
            [[ProductCollection::MAIN_TABLE_ALIAS, 'entity_id', 'product_id'], ['dp', 'document_id', 'document_id']]
        );
    }
}
