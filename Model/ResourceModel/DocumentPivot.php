<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\DocumentProductLink\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\ResourceModel\Db\VersionControl\AbstractDb;

class DocumentPivot extends AbstractDb
{
    protected function _construct(): void
    {
        $this->_init('opengento_document_pivot', 'pivot_id');
    }

    /**
     * @inheritdoc
     * @throws LocalizedException
     */
    public function insertOrUpdatePivot(int $documentId, string $identifier): void
    {
        $this->getConnection()->insertOnDuplicate(
            $this->getMainTable(),
            ['document_id' => $documentId, 'identifier' => $identifier]
        );
    }

    /**
     * @inheritdoc
     * @throws LocalizedException
     */
    public function getPivotIdentifier(int $documentId): string
    {
        return (string) $this->getConnection()->fetchOne($this->getConnection()
            ->select()
            ->from($this->getMainTable(), 'identifier')
            ->where('document_id=?', $documentId)
        );
    }
}
