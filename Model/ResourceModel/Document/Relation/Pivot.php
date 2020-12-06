<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\DocumentProductLink\Model\ResourceModel\Document\Relation;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\VersionControl\RelationInterface;
use Opengento\Document\Api\Data\DocumentInterface;
use Opengento\DocumentProductLink\Model\ResourceModel\DocumentPivot;

final class Pivot implements RelationInterface
{
    /**
     * @var DocumentPivot
     */
    private $documentPivot;

    public function __construct(
        DocumentPivot $documentPivot
    ) {
        $this->documentPivot = $documentPivot;
    }

    /**
     * @inheritdoc
     * @throws LocalizedException
     */
    public function processRelation(AbstractModel $entity): void
    {
        if ($entity instanceof DocumentInterface && $entity->getExtensionAttributes()->getPivotIdentifier()) {
            $this->documentPivot->insertOrUpdatePivot(
                $entity->getId(),
                $entity->getExtensionAttributes()->getPivotIdentifier()
            );
        }
    }
}
