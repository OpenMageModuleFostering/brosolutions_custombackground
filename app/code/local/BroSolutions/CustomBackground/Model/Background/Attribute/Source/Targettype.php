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
 * Admin source model for Type Of Object
 *
 * @category    BroSolutions
 * @package     BroSolutions_CustomBackground
 * @author      Alex (silyadev@gmail.com)
 */
class BroSolutions_CustomBackground_Model_Background_Attribute_Source_Targettype extends Mage_Eav_Model_Entity_Attribute_Source_Table
{
    /**
     * get possible values
     *
     * @access public
     * @param bool $withEmpty
     * @param bool $defaultValues
     * @return array
     * @author Ultimate Module Creator
     */
    public function getAllOptions($withEmpty = true, $defaultValues = false)
    {
        $options =  array(
            array(
                'label' => Mage::helper('brosolutions_custombackground')->__('CMS'),
                'value' => BroSolutions_CustomBackground_Model_Background::TARGET_CMS,
            ),
            array(
                'label' => Mage::helper('brosolutions_custombackground')->__('Product View'),
                'value' => BroSolutions_CustomBackground_Model_Background::TARGET_PRODUCT,
            ),
            array(
                'label' => Mage::helper('brosolutions_custombackground')->__('Category View'),
                'value' => BroSolutions_CustomBackground_Model_Background::TARGET_CATEGORY,
            ),
            array(
                'label' => Mage::helper('brosolutions_custombackground')->__('Customer Cabinet'),
                'value' => BroSolutions_CustomBackground_Model_Background::TARGET_CUSTOMER,
            ),
            array(
                'label' => Mage::helper('brosolutions_custombackground')->__('Home Page'),
                'value' => BroSolutions_CustomBackground_Model_Background::TARGET_HOME,
            ),
            array(
                'label' => Mage::helper('brosolutions_custombackground')->__('Checkout'),
                'value' => BroSolutions_CustomBackground_Model_Background::TARGET_CHECKOUT,
            ),
            array(
                'label' => Mage::helper('brosolutions_custombackground')->__('Cart'),
                'value' => BroSolutions_CustomBackground_Model_Background::TARGET_CART,
            )
        );
        if ($withEmpty) {
            array_unshift($options, array('label'=>'', 'value'=>''));
        }
        return $options;

    }

    /**
     * get options as array
     *
     * @access public
     * @param bool $withEmpty
     * @return string
     * @author Ultimate Module Creator
     */
    public function getOptionsArray($withEmpty = true)
    {
        $options = array();
        foreach ($this->getAllOptions($withEmpty) as $option) {
            $options[$option['value']] = $option['label'];
        }
        return $options;
    }

    /**
     * get option text
     *
     * @access public
     * @param mixed $value
     * @return string
     * @author Ultimate Module Creator
     */
    public function getOptionText($value)
    {
        $options = $this->getOptionsArray();
        if (!is_array($value)) {
            $value = explode(',', $value);
        }
        $texts = array();
        foreach ($value as $v) {
            if (isset($options[$v])) {
                $texts[] = $options[$v];
            }
        }
        return implode(', ', $texts);
    }
}
