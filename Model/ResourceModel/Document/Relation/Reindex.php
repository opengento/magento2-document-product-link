<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\DocumentProductLink\Model\ResourceModel\Document\Relation;

use Magento\Framework\Indexer\ActionFactory;
use Magento\Framework\Indexer\IndexerRegistry;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\VersionControl\RelationInterface;
use Opengento\Document\Api\Data\DocumentInterface;
use Opengento\DocumentProductLink\Model\Config\LinkProvider;
use Opengento\DocumentProductLink\Model\Mview\DocumentLink;

final class Reindex implements RelationInterface
{
    /**
     * @var IndexerRegistry
     */
    private $indexerRegistry;

    /**
     * @var ActionFactory
     */
    private $actionFactory;

    /**
     * @var LinkProvider
     */
    private $linkProvider;

    public function __construct(
        IndexerRegistry $indexerRegistry,
        ActionFactory $actionFactory,
        LinkProvider $linkProvider
    ) {
        $this->indexerRegistry = $indexerRegistry;
        $this->actionFactory = $actionFactory;
        $this->linkProvider = $linkProvider;
    }

    public function processRelation(AbstractModel $entity): void
    {
        if ($entity instanceof DocumentInterface && $this->linkProvider->hasPivotField($entity->getTypeId())) {
            $indexer = $this->indexerRegistry->get(DocumentLink::INDEXER_ID);
            if (!$indexer->isScheduled()) {
                $this->actionFactory->create($indexer->getActionClass())->executeRow($entity->getId());
            }
        }
    }
}
