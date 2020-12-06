<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\DocumentProductLink\Model\Document\Collection;

use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Data\CollectionModifierInterface;

/**
 * @api
 */
final class CollectionModifier implements CollectionModifierInterface
{
    /**
     * @var CollectionModifierInterface[]
     */
    private $modifiers;

    public function __construct(
        array $modifiers = []
    ) {
        $this->modifiers = $modifiers;
    }

    public function apply(AbstractDb $collection): void
    {
        foreach ($this->modifiers as $modifier) {
            $modifier->apply($collection);
        }
    }
}
