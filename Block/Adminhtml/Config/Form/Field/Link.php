<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\DocumentProductLink\Block\Adminhtml\Config\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Framework\View\Element\Html\Select;
use Opengento\DocumentProductLink\Block\Adminhtml\Config\Form\Field\Select\DocumentType;
use Opengento\DocumentProductLink\Block\Adminhtml\Config\Form\Field\Select\ProductAttribute;

class Link extends AbstractFieldArray
{
    /**
     * @throws LocalizedException
     */
    public function getDocumentTypeSelectRenderer(): Select
    {
        if (!$this->hasData('document_types_select_renderer')) {
            $this->setData(
                'document_types_select_renderer',
                $this->getLayout()->createBlock(
                    DocumentType::class,
                    '',
                    ['data' => ['is_render_to_js_template' => true, 'extra_params' => 'multiple="multiple"']]
                )
            );
        }

        return $this->getData('document_types_select_renderer');
    }

    /**
     * @throws LocalizedException
     */
    public function getProductAttributeSelectRenderer(): Select
    {
        if (!$this->hasData('product_attribute_select_renderer')) {
            $this->setData(
                'product_attribute_select_renderer',
                $this->getLayout()->createBlock(
                    ProductAttribute::class,
                    '',
                    ['data' => ['is_render_to_js_template' => true]]
                )
            );
        }

        return $this->getData('product_attribute_select_renderer');
    }

    /**
     * @throws LocalizedException
     */
    protected function _prepareToRender(): void
    {
        $this->addColumn(
            'document_type_ids',
            [
                'label' => new Phrase('Document Types'),
                'class' => 'required-entry',
                'renderer' => $this->getDocumentTypeSelectRenderer(),
            ]
        );
        $this->addColumn(
            'attribute_code',
            [
                'label' => new Phrase('Product Attribute Code'),
                'class' => 'required-entry',
                'renderer' => $this->getProductAttributeSelectRenderer(),
            ]
        );

        $this->_addAfter = false;
        $this->_addButtonLabel = (new Phrase('Add Link'))->render();
    }

    /**
     * @param DataObject $row
     * @throws LocalizedException
     */
    protected function _prepareArrayRow(DataObject $row): void
    {
        $options = [
            'option_' . $this->getProductAttributeSelectRenderer()->calcOptionHash($row->getData('attribute_code')) => 'selected="selected"'
        ];
        foreach ((array) $row->getData('document_type_ids') as $value) {
            $options['option_' . $this->getDocumentTypeSelectRenderer()->calcOptionHash($value)] = 'selected="selected"';
        }

        $row->setData('option_extra_attrs', $options);
    }
}
