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
 * Background admin edit form
 *
 * @category    BroSolutions
 * @package     BroSolutions_CustomBackground
 * @author      Alex (silyadev@gmail.com)
 */
class BroSolutions_CustomBackground_Block_Adminhtml_Background_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'brosolutions_custombackground';
        $this->_controller = 'adminhtml_background';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('brosolutions_custombackground')->__('Save Background')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('brosolutions_custombackground')->__('Delete Background')
        );
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('brosolutions_custombackground')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ),
            -100
        );
        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * get the edit form header
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getHeaderText()
    {
        if (Mage::registry('current_background') && Mage::registry('current_background')->getId()) {
            return Mage::helper('brosolutions_custombackground')->__(
                "Edit Background '%s'",
                $this->escapeHtml(Mage::registry('current_background')->getName())
            );
        } else {
            return Mage::helper('brosolutions_custombackground')->__('Add Background');
        }
    }
}
