<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\DocumentProductLink\Ui\DataProvider\Document\Form\Modifier;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;

final class ProductLink implements ModifierInterface
{
    /**
     * @var Collection
     */
    private $productCollection;

    public function __construct(
        Collection $productCollection
    ) {
        $this->productCollection = $productCollection;
    }

    /**
     * @inheritdoc
     * @throws LocalizedException
     */
    public function modifyData(array $data): array
    {
        foreach ($data as &$document) {
            $document['document_product_links_grid'] = [];
            $products = $this->productCollection->joinTable(
                ['pl'=>'opengento_document_product_link'],
                'product_id=entity_id',
                ['product' => 'product_id'],
                ['document_id' => $document['entity_id']]
            )->addAttributeToSelect('name');

            foreach ($products as $linkItem) {
                $document['document_product_links_grid'][] = [
                    'entity_id' => $linkItem->getId(),
                    'name' => $linkItem->getName(),
                    'sku' => $linkItem->getSku(),
                    'type_id' => $linkItem->getTypeId()
                ];
            }
        }

        return $data;
    }

    public function modifyMeta(array $meta): array
    {
        return $meta;
    }
}
