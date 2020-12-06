<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\DocumentProductLink\Block\Adminhtml\Config\Form\Field\Select;

use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;
use Opengento\Document\Model\Config\Source\DocumentTypes;
use function strpos;

class DocumentType extends Select
{
    /**
     * @var DocumentTypes
     */
    private $documentTypes;

    public function __construct(
        Context $context,
        DocumentTypes $documentTypes,
        array $data = []
    ) {
        $this->documentTypes = $documentTypes;
        parent::__construct($context, $data);
    }

    public function setInputName(string $inputName): self
    {
        return $this->setData(
            'name',
            $inputName . (strpos($this->getData('extra_params'), 'multiple') !== false ? '[]' : '')
        );
    }

    protected function _toHtml(): string
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->documentTypes->toOptionArray());
        }

        return parent::_toHtml();
    }
}
