<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\DocumentProductLink\Model\Config\Backend;

use Magento\Config\Model\Config\Backend\Serialized\ArraySerialized;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Indexer\IndexerRegistry;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\Serializer\Json;
use Opengento\DocumentProductLink\Model\Mview\DocumentLink;
use Opengento\DocumentProductLink\Model\Mview\ProductLink;

class Indexer extends ArraySerialized
{
    /**
     * @var IndexerRegistry
     */
    private $indexerRegistry;

    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        IndexerRegistry $indexerRegistry,
        ?AbstractResource $resource = null,
        ?AbstractDb $resourceCollection = null,
        array $data = [],
        ?Json $serializer = null
    ) {
        $this->indexerRegistry = $indexerRegistry;
        parent::__construct(
            $context,
            $registry,
            $config,
            $cacheTypeList,
            $resource,
            $resourceCollection,
            $data,
            $serializer
        );
    }

    public function afterSave(): self
    {
        if ($this->isValueChanged()) {
            $this->indexerRegistry->get(DocumentLink::INDEXER_ID)->invalidate();
            $this->indexerRegistry->get(ProductLink::INDEXER_ID)->invalidate();
        }

        return parent::afterSave();
    }
}
