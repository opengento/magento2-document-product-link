<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\DocumentProductLink\Block\Adminhtml\Config\Form\Field\Select;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Config;
use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;

class ProductAttribute extends Select
{
    /**
     * @var Config
     */
    private $config;

    public function __construct(
        Context $context,
        Config $config,
        array $data = []
    ) {
        $this->config = $config;
        parent::__construct($context, $data);
    }

    public function setInputName(string $inputName): self
    {
        return $this->setData('name', $inputName);
    }

    protected function _toHtml(): string
    {
        if (!$this->getOptions()) {
            $options = [];
            foreach ($this->config->getEntityAttributes(Product::ENTITY) as $attribute) {
                $options[] = [
                    'value' => $attribute->getAttributeCode(),
                    'label' => $attribute->getDefaultFrontendLabel(),
                ];
            }
            $this->setOptions($options);
        }

        return parent::_toHtml();
    }
}
