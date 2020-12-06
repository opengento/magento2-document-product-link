<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\DocumentProductLink\Api;

/**
 * @api
 */
interface LinkManagementInterface
{
    /**
     * @param int $documentId
     * @param int[] $productIds
     * @return void
     */
    public function setLinks(int $documentId, array $productIds): void;
}
