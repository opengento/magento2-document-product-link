<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\DocumentProductLink\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Zend_Db_Exception;

final class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context): void
    {
        $setup->startSetup();

        $this->installDocumentPivotTable($setup);
        $this->installDocumentProductLinkTable($setup);

        $setup->endSetup();
    }

    /**
     * @param SchemaSetupInterface $setup
     * @return void
     * @throws Zend_Db_Exception
     */
    private function installDocumentPivotTable(SchemaSetupInterface $setup): void
    {
        $connection = $setup->getConnection();
        $tableName = $connection->getTableName('opengento_document_pivot');
        $newTable = $connection->newTable($tableName)
            ->addColumn(
                'pivot_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
                'Pivot ID'
            )->addColumn(
                'document_id',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true],
                'Document ID'
            )->addColumn(
                'identifier',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Pivot Identifier'
            )->addIndex(
                $connection->getIndexName($tableName, ['identifier']),
                ['identifier']
            )->addIndex(
                $connection->getIndexName($tableName, ['document_id'], AdapterInterface::INDEX_TYPE_UNIQUE),
                ['document_id'],
                ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
            )->addForeignKey(
                $setup->getFkName(
                    'opengento_document_pivot',
                    'document_id',
                    'opengento_document',
                    'entity_id'
                ),
                'document_id',
                $setup->getTable('opengento_document'),
                'entity_id',
                Table::ACTION_CASCADE
            );
        $connection->createTable($newTable);
    }

    /**
     * @param SchemaSetupInterface $setup
     * @return void
     * @throws Zend_Db_Exception
     */
    private function installDocumentProductLinkTable(SchemaSetupInterface $setup): void
    {
        $connection = $setup->getConnection();
        $tableName = $connection->getTableName('opengento_document_product_link');
        $newTable = $connection->newTable($tableName)
            ->addColumn(
                'link_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true],
                'Link ID'
            )->addColumn(
                'document_id',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true],
                'Document ID'
            )->addColumn(
                'product_id',
                Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true],
                'Product ID'
            )->addColumn(
                'is_user_defined',
                Table::TYPE_BOOLEAN,
                null,
                ['nullable' => false, 'default' => 0],
                'Is Link Defined by User'
            )->addIndex(
                $setup->getIdxName(
                    'opengento_document_product_link',
                    ['is_user_defined'],
                    AdapterInterface::INDEX_TYPE_INDEX
                ),
                ['is_user_defined'],
                ['type' => AdapterInterface::INDEX_TYPE_INDEX]
            )->addIndex(
                $setup->getIdxName(
                    'opengento_document_product_link',
                    ['document_id', 'product_id'],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['document_id', 'product_id'],
                ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
            )->addForeignKey(
                $setup->getFkName(
                    'opengento_document_product_link',
                    'document_id',
                    'opengento_document',
                    'entity_id'
                ),
                'document_id',
                $setup->getTable('opengento_document'),
                'entity_id',
                Table::ACTION_CASCADE
            )->addForeignKey(
                $setup->getFkName(
                    'opengento_document_product_link',
                    'product_id',
                    'catalog_product_entity',
                    'entity_id'
                ),
                'product_id',
                $setup->getTable('catalog_product_entity'),
                'entity_id',
                Table::ACTION_CASCADE
            );
        $connection->createTable($newTable);
    }
}
