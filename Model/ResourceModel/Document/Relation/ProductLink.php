<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\DocumentProductLink\Model\ResourceModel\Document\Relation;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\VersionControl\RelationInterface;
use Opengento\Document\Api\Data\DocumentInterface;
use Opengento\DocumentProductLink\Api\LinkManagementInterface;
use function array_column;

final class ProductLink implements RelationInterface
{
    /**
     * @var LinkManagementInterface
     */
    private $linkManagement;

    public function __construct(
        LinkManagementInterface $linkManagement
    ) {
        $this->linkManagement = $linkManagement;
    }

    public function processRelation(AbstractModel $entity): void
    {
        if ($entity instanceof DocumentInterface && $entity->hasData('document_product_listing')) {
            $productLinks = (array) $entity->getData('document_product_listing');
            if ($productLinks) {
                $this->linkManagement->setLinks($entity->getId(), array_column($productLinks, 'entity_id'));
            }
        }
    }
}
