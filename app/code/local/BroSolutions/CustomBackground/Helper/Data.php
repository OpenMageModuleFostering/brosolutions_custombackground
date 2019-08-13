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
 * CustomBackground default helper
 *
 * @category    BroSolutions
 * @package     BroSolutions_CustomBackground
 * @author      Alex (silyadev@gmail.com)
 */
class BroSolutions_CustomBackground_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * convert array to options
     *
     * @access public
     * @param $options
     * @return array
     * @author Ultimate Module Creator
     */
    public function convertOptions($options)
    {
        $converted = array();
        foreach ($options as $option) {
            if (isset($option['value']) && !is_array($option['value']) &&
                isset($option['label']) && !is_array($option['label'])) {
                $converted[$option['value']] = $option['label'];
            }
        }
        return $converted;
    }
    
    public function getObjectsByType($type)
    {
        switch ($type){
            case BroSolutions_CustomBackground_Model_Background::TARGET_CMS:
                $collection = Mage::getModel('cms/page')
                    ->getCollection()
                    ->addFieldToFilter('is_active',1);
                break;
            default:
                return false;
        }
        
        return $collection->toOptionArray();    
    }
    
    
    public function getCurrentBackground()
    {
        switch(Mage::app()->getRequest()->getControllerName()){
            case 'index':
                $background = Mage::getModel('brosolutions_custombackground/background')
                        ->getCollection()
                        ->addStoreFilter(Mage::app()->getStore())
                        ->addFieldToFilter('status',1)
                        ->addFieldToFilter('target_type',BroSolutions_CustomBackground_Model_Background::TARGET_HOME)
                        ->getFirstItem();
                break;
            case 'page':
                $background = Mage::getModel('brosolutions_custombackground/background')
                        ->getCollection()
                        ->addFieldToFilter('status',1)
                        ->addStoreFilter(Mage::app()->getStore())
                        ->addFieldToFilter('target_type',BroSolutions_CustomBackground_Model_Background::TARGET_CMS)
                        ->addFieldToFilter('target_id',array('regexp'=>'(^|,)'.Mage::getSingleton('cms/page')->getIdentifier().'(,|$)'))
                        ->getFirstItem();
                break;

            case 'category':
                $background = Mage::getModel('brosolutions_custombackground/background')
                        ->getCollection()
                        ->addStoreFilter(Mage::app()->getStore())
                        ->addFieldToFilter('status',1)
                        ->addFieldToFilter('target_type',BroSolutions_CustomBackground_Model_Background::TARGET_CATEGORY)
                        ->getFirstItem();
                break;
            case 'product':
                $background = Mage::getModel('brosolutions_custombackground/background')
                        ->getCollection()
                        ->addStoreFilter(Mage::app()->getStore())
                        ->addFieldToFilter('status',1)
                        ->addFieldToFilter('target_type',BroSolutions_CustomBackground_Model_Background::TARGET_CATEGORY)
                        ->getFirstItem();
                break;
            case 'cart':
                $background = Mage::getModel('brosolutions_custombackground/background')
                        ->getCollection()
                        ->addStoreFilter(Mage::app()->getStore())
                        ->addFieldToFilter('status',1)
                        ->addFieldToFilter('target_type',BroSolutions_CustomBackground_Model_Background::TARGET_CART)
                        ->getFirstItem();
                break;
            case 'onepage':
            case 'checkout':
                $background = Mage::getModel('brosolutions_custombackground/background')
                        ->getCollection()
                        ->addStoreFilter(Mage::app()->getStore())
                        ->addFieldToFilter('status',1)
                        ->addFieldToFilter('target_type',BroSolutions_CustomBackground_Model_Background::TARGET_CHECKOUT)
                        ->getFirstItem();
                break;
            case 'account':
                $background = Mage::getModel('brosolutions_custombackground/background')
                        ->getCollection()
                        ->addStoreFilter(Mage::app()->getStore())
                        ->addFieldToFilter('status',1)
                        ->addFieldToFilter('target_type',BroSolutions_CustomBackground_Model_Background::TARGET_CUSTOMER)
                        ->getFirstItem();
                break;
            
            default: Mage::getModel('brosolutions_custombackground/background');
        }
        return $background;
    }
}
