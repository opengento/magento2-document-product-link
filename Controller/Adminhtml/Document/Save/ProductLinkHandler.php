<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\DocumentProductLink\Controller\Adminhtml\Document\Save;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Model\AbstractModel;
use Opengento\Document\Api\Data\DocumentInterface;
use Opengento\Document\Controller\Adminhtml\Index\Save\HandlerInterface;

final class ProductLinkHandler implements HandlerInterface
{
    public function execute(RequestInterface $request, DocumentInterface $document): DocumentInterface
    {
        if ($document instanceof AbstractModel) {
            $document->setData('document_product_listing', (array) $request->getParam('document_product_listing'));
        }

        return $document;
    }

    public function rollback(RequestInterface $request): void
    {
        /** Silence is golden... */
    }
}
