<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\DocumentProductLink\Controller\Adminhtml\Document\Save;

use Magento\Framework\App\RequestInterface;
use Opengento\Document\Api\Data\DocumentInterface;
use Opengento\Document\Controller\Adminhtml\Index\Save\HandlerInterface;

final class PivotHandler implements HandlerInterface
{
    public function execute(RequestInterface $request, DocumentInterface $document): DocumentInterface
    {
        $document->getExtensionAttributes()->setPivotIdentifier($request->getParam('pivot_identifier'));

        return $document;
    }

    public function rollback(RequestInterface $request): void
    {
        /** Silence is golden... */
    }
}
