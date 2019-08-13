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
 * Background admin block
 *
 * @category    BroSolutions
 * @package     BroSolutions_CustomBackground
 * @author      Alex (silyadev@gmail.com)
 */

class BroSolutions_CustomBackground_Block_Background extends Mage_Core_Block_Template
{
    
    public function getBackground()
    {
        if(!$this->getData('current_bro_background')){
            $background = Mage::helper('brosolutions_custombackground')->getCurrentBackground();
            $this->setData('current_bro_background',$background);
        }
        return $this->getData('current_bro_background');
    }
            
    
    public function isBackgroundNeeded()
    {
        return (bool)$this->getBackground();
    }
    
    public function getStyleOptions(){
        $result = array();
        $background = $this->getBackground();
        if(strlen($background->getColor())){
            $result['backgroundColor'] = '#'.$background->getColor();
        }
        
        if(strlen($background->getBackground())){
            $result['backgroundImage'] = 'url('.Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'background/image'.$background->getBackground().')';
        }
            
        if($background->getRepeatX() && $background->getRepeatY()){
            $result['backgroundRepeat'] = 'repeat';
        } elseif($background->getRepeatX()){
            $result['backgroundRepeat'] = 'repeat-x';
        } elseif($background->getRepeatY()){
            $result['backgroundRepeat'] = 'repeat-y';
        }
        
         if(strlen($background->getStyle())){
             foreach(explode(';',$background->getStyle()) as $pair){
                 if(!stristr($pair, ':'))
                         continue;
                 list($key,$value) = explode(':',$pair);
                 $result[$key] = $value;
             }
         }
         
         return $result;
    }
    
    
    public function getTargetElement()
    {
        $background = $this->getBackground();
        return $background->getTargetElement();
    }
}

