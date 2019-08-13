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
 * Background admin controller
 *
 * @category    BroSolutions
 * @package     BroSolutions_CustomBackground
 * @author      Alex (silyadev@gmail.com)
 */
class BroSolutions_CustomBackground_Adminhtml_Custombackground_BackgroundController extends BroSolutions_CustomBackground_Controller_Adminhtml_CustomBackground
{
    /**
     * init the background
     *
     * @access protected
     * @return BroSolutions_CustomBackground_Model_Background
     */
    protected function _initBackground()
    {
        $backgroundId  = (int) $this->getRequest()->getParam('id');
        $background    = Mage::getModel('brosolutions_custombackground/background');
        if ($backgroundId) {
            $background->load($backgroundId);
        }
        Mage::register('current_background', $background);
        return $background;
    }

    /**
     * default action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_title(Mage::helper('brosolutions_custombackground')->__('Background Management'))
             ->_title(Mage::helper('brosolutions_custombackground')->__('Backgrounds'));
        $this->renderLayout();
    }

    /**
     * grid action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
    }

    /**
     * edit background - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function editAction()
    {
        $backgroundId    = $this->getRequest()->getParam('id');
        $background      = $this->_initBackground();
        if ($backgroundId && !$background->getId()) {
            $this->_getSession()->addError(
                Mage::helper('brosolutions_custombackground')->__('This background no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getBackgroundData(true);
        if (!empty($data)) {
            $background->setData($data);
        }
        Mage::register('background_data', $background);
        $this->loadLayout();
        $this->_title(Mage::helper('brosolutions_custombackground')->__('Background Management'))
             ->_title(Mage::helper('brosolutions_custombackground')->__('Backgrounds'));
        if ($background->getId()) {
            $this->_title($background->getName());
        } else {
            $this->_title(Mage::helper('brosolutions_custombackground')->__('Add background'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new background action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * save background - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('background')) {
            try {
                $background = $this->_initBackground();
                $background->addData($data);
                $backgroundName = $this->_uploadAndGetName(
                    'background',
                    Mage::helper('brosolutions_custombackground/background_image')->getImageBaseDir(),
                    $data
                );
                $background->setData('background', $backgroundName);
                $background->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('brosolutions_custombackground')->__('Background was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $background->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                if (isset($data['background']['value'])) {
                    $data['background'] = $data['background']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setBackgroundData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                if (isset($data['background']['value'])) {
                    $data['background'] = $data['background']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('brosolutions_custombackground')->__('There was a problem saving the background.')
                );
                Mage::getSingleton('adminhtml/session')->setBackgroundData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('brosolutions_custombackground')->__('Unable to find background to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete background - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $background = Mage::getModel('brosolutions_custombackground/background');
                $background->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('brosolutions_custombackground')->__('Background was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('brosolutions_custombackground')->__('There was an error deleting background.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('brosolutions_custombackground')->__('Could not find background to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete background - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massDeleteAction()
    {
        $backgroundIds = $this->getRequest()->getParam('background');
        if (!is_array($backgroundIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('brosolutions_custombackground')->__('Please select backgrounds to delete.')
            );
        } else {
            try {
                foreach ($backgroundIds as $backgroundId) {
                    $background = Mage::getModel('brosolutions_custombackground/background');
                    $background->setId($backgroundId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('brosolutions_custombackground')->__('Total of %d backgrounds were successfully deleted.', count($backgroundIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('brosolutions_custombackground')->__('There was an error deleting backgrounds.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass status change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massStatusAction()
    {
        $backgroundIds = $this->getRequest()->getParam('background');
        if (!is_array($backgroundIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('brosolutions_custombackground')->__('Please select backgrounds.')
            );
        } else {
            try {
                foreach ($backgroundIds as $backgroundId) {
                $background = Mage::getSingleton('brosolutions_custombackground/background')->load($backgroundId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d backgrounds were successfully updated.', count($backgroundIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('brosolutions_custombackground')->__('There was an error updating backgrounds.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Type Of Object change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massTargetTypeAction()
    {
        $backgroundIds = $this->getRequest()->getParam('background');
        if (!is_array($backgroundIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('brosolutions_custombackground')->__('Please select backgrounds.')
            );
        } else {
            try {
                foreach ($backgroundIds as $backgroundId) {
                $background = Mage::getSingleton('brosolutions_custombackground/background')->load($backgroundId)
                    ->setTargetType($this->getRequest()->getParam('flag_target_type'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d backgrounds were successfully updated.', count($backgroundIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('brosolutions_custombackground')->__('There was an error updating backgrounds.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Repeat-X change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massRepeatXAction()
    {
        $backgroundIds = $this->getRequest()->getParam('background');
        if (!is_array($backgroundIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('brosolutions_custombackground')->__('Please select backgrounds.')
            );
        } else {
            try {
                foreach ($backgroundIds as $backgroundId) {
                $background = Mage::getSingleton('brosolutions_custombackground/background')->load($backgroundId)
                    ->setRepeatX($this->getRequest()->getParam('flag_repeat_x'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d backgrounds were successfully updated.', count($backgroundIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('brosolutions_custombackground')->__('There was an error updating backgrounds.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Repeat-Y change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massRepeatYAction()
    {
        $backgroundIds = $this->getRequest()->getParam('background');
        if (!is_array($backgroundIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('brosolutions_custombackground')->__('Please select backgrounds.')
            );
        } else {
            try {
                foreach ($backgroundIds as $backgroundId) {
                $background = Mage::getSingleton('brosolutions_custombackground/background')->load($backgroundId)
                    ->setRepeatY($this->getRequest()->getParam('flag_repeat_y'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d backgrounds were successfully updated.', count($backgroundIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('brosolutions_custombackground')->__('There was an error updating backgrounds.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * export as csv - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportCsvAction()
    {
        $fileName   = 'background.csv';
        $content    = $this->getLayout()->createBlock('brosolutions_custombackground/adminhtml_background_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as MsExcel - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportExcelAction()
    {
        $fileName   = 'background.xls';
        $content    = $this->getLayout()->createBlock('brosolutions_custombackground/adminhtml_background_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as xml - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportXmlAction()
    {
        $fileName   = 'background.xml';
        $content    = $this->getLayout()->createBlock('brosolutions_custombackground/adminhtml_background_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    
    public function getTargetAction()
    {
        $type = $this->getRequest()->getParam('type');
        $options = Mage::helper('brosolutions_custombackground')->getObjectsByType($type);
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('background_');
        $form->setFieldNameSuffix('background');

        if($options){
                $config = array(
                            'label' => Mage::helper('brosolutions_custombackground')->__('Object'),
                            'name'  => 'target_id',
                            'required'  => true,
                            'class' => 'required-entry',
                            'values' => $options,
                           );
            $element = new Varien_Data_Form_Element_Multiselect($config);
        } else {
            $config = array(
                    'label' => Mage::helper('brosolutions_custombackground')->__('Object'),
                    'name'  => 'target_id',
                    'required'  => true,
                    'class' => 'required-entry',
                   );
            $element = new Varien_Data_Form_Element_Hidden($config);
        }
        
        $element->setId('target_id');
        $element->setForm($form);

        $renderer = $this->getLayout()->createBlock('adminhtml/widget_form_renderer_element');
        $element->setRenderer($renderer);
        
        $this->getResponse()->setBody($element->getElementHtml());
    }
    

    /**
     * Check if admin has permissions to visit related pages
     *
     * @access protected
     * @return boolean
     * @author Ultimate Module Creator
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/brosolutions_custombackground/background');
    }
}
