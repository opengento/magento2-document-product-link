<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\DocumentProductLink\Model\Mview;

use Magento\Framework\Mview\ActionInterface;
use Opengento\DocumentProductLink\Model\Indexer\ProductLink as ProductLinkIndexer;

final class ProductLink implements ActionInterface
{
    public const INDEXER_ID = 'opengento_product_document_link';

    /**
     * @var ProductLinkIndexer
     */
    private $productLinkIndexer;

    public function __construct(
        ProductLinkIndexer $productLinkIndexer
    ) {
        $this->productLinkIndexer = $productLinkIndexer;
    }

    public function execute($ids): void
    {
        $this->productLinkIndexer->executeList((array) $ids);
    }
}
