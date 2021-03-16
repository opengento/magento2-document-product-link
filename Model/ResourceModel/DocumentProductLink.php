<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\DocumentProductLink\Model\ResourceModel;

use Magento\Framework\DB\Adapter\Pdo\Mysql;
use Magento\Framework\DB\Select;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\Model\ResourceModel\Db\VersionControl\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\VersionControl\RelationComposite;
use Magento\Framework\Model\ResourceModel\Db\VersionControl\Snapshot;
use Opengento\DocumentProductLink\Model\ResourceModel\DocumentProductLink\LinkSelectFactory;
use function array_column;
use function array_map;
use function array_reduce;

class DocumentProductLink extends AbstractDb
{
    /**
     * @var LinkSelectFactory
     */
    private $linkSelectFactory;

    public function __construct(
        Context $context,
        Snapshot $entitySnapshot,
        RelationComposite $relationComposite,
        LinkSelectFactory $linkSelectFactory,
        ?String $connectionName = null
    ) {
        $this->linkSelectFactory = $linkSelectFactory;
        parent::__construct($context, $entitySnapshot, $relationComposite, $connectionName);
    }

    protected function _construct(): void
    {
        $this->_init('opengento_document_product_link', 'link_id');
    }

    public function insertLinks(): void
    {
        foreach ($this->linkSelectFactory->createSelects() as $select) {
            $this->insertLinksBySelect($select);
        }
    }

    public function insertLinksByDocuments(array $documentIds = []): void
    {
        foreach ($this->linkSelectFactory->createSelectsByDocuments($documentIds) as $select) {
            $this->insertLinksBySelect($select);
        }
    }

    public function insertLinksByProducts(array $productIds = []): void
    {
        foreach ($this->linkSelectFactory->createSelects() as $select) {
            if ($productIds) {
                $select->where('e.entity_id IN (?)', $productIds);
            }
            $this->insertLinksBySelect($select);
        }
    }

    public function deleteIndexerLinks(): void
    {
        $this->getConnection()->delete($this->getMainTable(), ['is_user_defined=?' => 0]);
    }

    public function deleteLinksByDocuments(array $documentIds = []): void
    {
        if ($documentIds) {
            $this->getConnection()->delete(
                $this->getMainTable(),
                ['is_user_defined=?' => 0, 'document_id IN (?)' => $documentIds]
            );
        } else {
            $this->deleteIndexerLinks();
        }
    }

    public function deleteLinksByProducts(array $productIds = []): void
    {
        if ($productIds) {
            $this->getConnection()->delete(
                $this->getMainTable(),
                ['is_user_defined=?' => 0, 'product_id IN (?)' => $productIds]
            );
        } else {
            $this->deleteIndexerLinks();
        }
    }

    public function setDocumentProductLinks(int $documentId, array $productIds): void
    {
        $connection = $this->getConnection();

        $existingLinks = $connection->fetchAll(
            $connection->select()->from($this->getMainTable(), 'product_id')->where('document_id = ?', $documentId)
        );

        $savedProductIds = array_column($existingLinks, 'product_id');
        $removeLinks = array_diff($savedProductIds, $productIds);
        $addLinks = array_diff($productIds, $savedProductIds);

        if ($removeLinks) {
            $connection->delete(
                $this->getMainTable(),
                ['document_id=?' => $documentId, 'product_id IN (?)' => $removeLinks]
            );
        }
        if ($addLinks) {
            $connection->insertArray(
                $this->getMainTable(),
                ['document_id', 'product_id', 'is_user_defined'],
                array_reduce(
                    array_map('\intval', $addLinks),
                    static function (array $data, int $productId) use ($documentId): array {
                        $data[] = [$documentId, $productId, 1];

                        return $data;
                    },
                    []
                )
            );
        }
    }

    private function insertLinksBySelect(Select $select): void
    {
        $this->getConnection()->query(
            $this->getConnection()->insertFromSelect(
                $select,
                $this->getMainTable(),
                ['product_id', 'document_id'],
                Mysql::INSERT_IGNORE
            )
        );
    }
}
