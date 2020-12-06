<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\DocumentProductLink\Model\ResourceModel\Product\Relation;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Magento\Framework\Indexer\ActionFactory;
use Magento\Framework\Indexer\IndexerRegistry;
use Opengento\DocumentProductLink\Model\Mview\ProductLink;

final class DocumentLink implements ExtensionInterface
{
    /**
     * @var IndexerRegistry
     */
    private $indexerRegistry;

    /**
     * @var ActionFactory
     */
    private $actionFactory;

    public function __construct(
        IndexerRegistry $indexerRegistry,
        ActionFactory $actionFactory
    ) {
        $this->indexerRegistry = $indexerRegistry;
        $this->actionFactory = $actionFactory;
    }

    public function execute($entity, $arguments = null): ProductInterface
    {
        if ($entity instanceof ProductInterface && $arguments) {
            $indexer = $this->indexerRegistry->get(ProductLink::INDEXER_ID);
            if (!$indexer->isScheduled()) {
                $this->actionFactory->create($indexer->getActionClass())->executeRow($entity->getId());
            }
        }

        return $entity;
    }
}
