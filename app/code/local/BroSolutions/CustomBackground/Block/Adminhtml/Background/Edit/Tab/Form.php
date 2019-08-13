<?php
/**
 * BroSolutions_CustomBackground extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       BroSolutions
 * @package        BroSolutions_CustomBackground
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Background edit form tab
 *
 * @category    BroSolutions
 * @package     BroSolutions_CustomBackground
 * @author      Alex (silyadev@gmail.com)
 */
class BroSolutions_CustomBackground_Block_Adminhtml_Background_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BroSolutions_CustomBackground_Block_Adminhtml_Background_Edit_Tab_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('background_');
        $form->setFieldNameSuffix('background');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'background_form',
            array('legend' => Mage::helper('brosolutions_custombackground')->__('Background'))
        );
        $fieldset->addType(
            'image',
            Mage::getConfig()->getBlockClassName('brosolutions_custombackground/adminhtml_background_helper_image')
        );

        $fieldset->addField(
            'name',
            'text',
            array(
                'label' => Mage::helper('brosolutions_custombackground')->__('Name'),
                'name'  => 'name',
            'required'  => true,
            'class' => 'required-entry',
           )
        );
        
        $fieldset->addField(
            'target_element',
            'select',
            array(
                'label' => Mage::helper('brosolutions_custombackground')->__('Append Background To'),
                'name'  => 'target_element',
                'required'  => true,
                'class' => 'required-entry',
                'values'=> array(
                    '.wrapper'=>'Wrapper',
                    'body'=>'Body',
                    'custom'=>'Custom CSS selector',
                ),
           )
        );

        
        $fieldset->addField(
            'target_element_custom',
            'text',
            array(
                'label' => Mage::helper('brosolutions_custombackground')->__('Custom CSS selector'),
                'name'  => 'target_element_custom',
                'required'  => false,
                'note'=>'Input CSS seletot, like .class or #id',
           )
        );
        
        $fieldset->addField(
            'target_type',
            'select',
            array(
                'label' => Mage::helper('brosolutions_custombackground')->__('Type Of Object'),
                'name'  => 'target_type',
            'required'  => true,
            'class' => 'required-entry',

            'values'=> Mage::getModel('brosolutions_custombackground/background_attribute_source_targettype')->getAllOptions(true),
           )
        );

        $options = $collection = Mage::getModel('cms/page')
                    ->getCollection()
                    ->addFieldToFilter('is_active',1)
                    ->toOptionArray();
        
        $fieldset->addField(
            'target_id',
            'multiselect',
            array(
                'label' => Mage::helper('brosolutions_custombackground')->__('Object'),
                'name'  => 'target_id',
                'required'  => true,
                'class' => 'required-entry',
                'values' => $options,
           )
        );

        $fieldset->addField(
            'background',
            'image',
            array(
                'label' => Mage::helper('brosolutions_custombackground')->__('Background'),
                'name'  => 'background',

           )
        );

        $fieldset->addField(
            'color',
            'text',
            array(
                'label' => Mage::helper('brosolutions_custombackground')->__('Background Color'),
                'name'  => 'color',
                'class' => 'color'
           )
        );
        

        $fieldset->addField(
            'repeat_x',
            'select',
            array(
                'label' => Mage::helper('brosolutions_custombackground')->__('Repeat-X'),
                'name'  => 'repeat_x',

            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('brosolutions_custombackground')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('brosolutions_custombackground')->__('No'),
                ),
            ),
           )
        );

        $fieldset->addField(
            'repeat_y',
            'select',
            array(
                'label' => Mage::helper('brosolutions_custombackground')->__('Repeat-Y'),
                'name'  => 'repeat_y',

            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('brosolutions_custombackground')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('brosolutions_custombackground')->__('No'),
                ),
            ),
           )
        );

        $fieldset->addField(
            'style',
            'text',
            array(
                'label' => Mage::helper('brosolutions_custombackground')->__('Additional CSS Styles'),
                'name'  => 'style',

           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('brosolutions_custombackground')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('brosolutions_custombackground')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('brosolutions_custombackground')->__('Disabled'),
                    ),
                ),
            )
        );
        if (Mage::app()->isSingleStoreMode()) {
            $fieldset->addField(
                'store_id',
                'hidden',
                array(
                    'name'      => 'stores[]',
                    'value'     => Mage::app()->getStore(true)->getId()
                )
            );
            Mage::registry('current_background')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $formValues = Mage::registry('current_background')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getBackgroundData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getBackgroundData());
            Mage::getSingleton('adminhtml/session')->setBackgroundData(null);
        } elseif (Mage::registry('current_background')) {
            $formValues = array_merge($formValues, Mage::registry('current_background')->getData());
        }
        $form->setValues($formValues);
        
        $this->setChild(
            'form_after',
            $this->getLayout()->createBlock('adminhtml/widget_form_element_dependence')
                ->addFieldMap('background_target_element', 'target_element')
                ->addFieldMap('background_target_element_custom', 'target_element_custom')
                ->addFieldDependence('target_element_custom', 'target_element', 'custom')
                ->addFieldMap('background_target_type','target_type')
                ->addFieldMap('background_target_id','target_id')
                ->addFieldDependence('target_id', 'target_type', array('1','3'))
        );
        
        return parent::_prepareForm();
    }

    
//    public function _afterToHtml($html) {
//        $html .= '<script> 
//                        Event.observe($("background_target_type"), "change", function(){
//                            new Ajax.Updater("background_target_id", "'.$this->getUrl('*/*/getTarget').'", {
//                                parameters: { type: $F("background_target_type") }
//                            });
//                        })
//                 </script>';
//        return parent::_afterToHtml($html);
//    }    
}
