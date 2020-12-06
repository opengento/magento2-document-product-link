<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\DocumentProductLink\Model\Mview;

use Magento\Framework\Mview\ActionInterface;
use Opengento\DocumentProductLink\Model\Indexer\DocumentLink as DocumentLinkIndexer;

final class DocumentLink implements ActionInterface
{
    public const INDEXER_ID = 'opengento_document_product_link';

    /**
     * @var DocumentLinkIndexer
     */
    private $documentLinkIndexer;

    public function __construct(
        DocumentLinkIndexer $documentLinkIndexer
    ) {
        $this->documentLinkIndexer = $documentLinkIndexer;
    }

    public function execute($ids): void
    {
        $this->documentLinkIndexer->executeList((array) $ids);
    }
}
