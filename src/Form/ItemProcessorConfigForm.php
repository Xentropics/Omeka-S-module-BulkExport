<?php
namespace BulkImport\Form;

use Omeka\Form\Element\ItemSetSelect;

class ItemProcessorConfigForm extends ResourceProcessorConfigForm
{
    protected function addFieldsets()
    {
        parent::addFieldsets();

        $this->add([
            'name' => 'o:item_set',
            'type' => ItemSetSelect::class,
            'options' => [
                'label' => 'Item set', // @translate
                'empty_option' => 'Select one or more item sets…', // @translate
            ],
            'attributes' => [
                'id' => 'o-item-set',
                'class' => 'chosen-select',
                'multiple' => true,
                'required' => false,
                'data-placeholder' => 'Select one or more item sets…', // @translate
            ],
        ]);
    }

    protected function addInputFilter()
    {
        parent::addInputFilter();

        $inputFilter = $this->getInputFilter();
        $inputFilter->add([
            'name' => 'o:item_set',
            'required' => false,
        ]);
    }
}
