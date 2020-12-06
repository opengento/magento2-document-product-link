<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\DocumentProductLink\Ui\DataProvider\Document\Form\Modifier;

use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Opengento\DocumentProductLink\Model\ResourceModel\DocumentPivot;

final class Pivot implements ModifierInterface
{
    /**
     * @var DocumentPivot
     */
    private $documentPivot;

    public function __construct(
        DocumentPivot $documentPivot
    ) {
        $this->documentPivot = $documentPivot;
    }

    /**
     * @inheritdoc
     * @throws LocalizedException
     */
    public function modifyData(array $data): array
    {
        foreach ($data as $documentId => &$document) {
            $document['pivot_identifier'] = $this->documentPivot->getPivotIdentifier((int) $documentId);
        }

        return $data;
    }

    public function modifyMeta(array $meta): array
    {
        return $meta;
    }
}
