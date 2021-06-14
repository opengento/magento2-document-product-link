<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\DocumentProductLink\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\SerializerInterface;

final class LinkProvider
{
    private const CONFIG_PATH_DOCUMENT_TYPE_PIVOT_FIELD = 'opengento_document_type/product_link/pivot_field';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var array|null
     */
    private $pivotFields;

    /**
     * @var string[]|null
     */
    private $pivotFieldByType;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        SerializerInterface $serializer
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->serializer = $serializer;
    }

    public function getPivotFields(): array
    {
        return $this->resolvePivotFields();
    }

    public function hasPivotField(int $documentTypeId): bool
    {
        return isset($this->resolvePivotFieldByType()[$documentTypeId]);
    }

    public function getPivotField(int $documentTypeId): string
    {
        return $this->resolvePivotFieldByType()[$documentTypeId] ?? '';
    }

    private function resolvePivotFieldByType(): array
    {
        if ($this->pivotFieldByType === null) {
            $this->loadPivotFields();
        }

        return $this->pivotFieldByType;
    }

    private function resolvePivotFields(): array
    {
        if ($this->pivotFields === null) {
            $this->loadPivotFields();
        }

        return $this->pivotFields;
    }

    private function loadPivotFields(): void
    {
        $pivotFields = $this->serializer->unserialize(
            $this->scopeConfig->getValue(self::CONFIG_PATH_DOCUMENT_TYPE_PIVOT_FIELD) ?? '{}'
        );

        $this->pivotFields = [];
        $this->pivotFieldByType = [];
        foreach ($pivotFields as $pivotField) {
            if (!isset($this->pivotFields[$pivotField['attribute_code']])) {
                $this->pivotFields[$pivotField['attribute_code']] = [];
            }
            $typeIds = (array) $pivotField['document_type_ids'];
            $this->pivotFields[$pivotField['attribute_code']][] = $typeIds;

            foreach ($typeIds as $typeId) {
                $this->pivotFieldByType[$typeId] = $pivotField['attribute_code'];
            }
        }
    }
}
