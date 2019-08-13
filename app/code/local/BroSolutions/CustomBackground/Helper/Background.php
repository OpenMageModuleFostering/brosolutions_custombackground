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
 * Background helper
 *
 * @category    BroSolutions
 * @package     BroSolutions_CustomBackground
 * @author      Alex (silyadev@gmail.com)
 */
class BroSolutions_CustomBackground_Helper_Background extends Mage_Core_Helper_Abstract
{

    /**
     * check if breadcrumbs can be used
     *
     * @access public
     * @return bool
     * @author Ultimate Module Creator
     */
    public function getUseBreadcrumbs()
    {
        return Mage::getStoreConfigFlag('brosolutions_custombackground/background/breadcrumbs');
    }
}
