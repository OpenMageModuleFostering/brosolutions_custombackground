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
 * Background resource model
 *
 * @category    BroSolutions
 * @package     BroSolutions_CustomBackground
 * @author      Alex (silyadev@gmail.com)
 */
class BroSolutions_CustomBackground_Model_Resource_Background extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * constructor
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function _construct()
    {
        $this->_init('brosolutions_custombackground/background', 'entity_id');
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @access public
     * @param int $backgroundId
     * @return array
     * @author Ultimate Module Creator
     */
    public function lookupStoreIds($backgroundId)
    {
        $adapter = $this->_getReadAdapter();
        $select  = $adapter->select()
            ->from($this->getTable('brosolutions_custombackground/background_store'), 'store_id')
            ->where('background_id = ?', (int)$backgroundId);
        return $adapter->fetchCol($select);
    }

    /**
     * Perform operations after object load
     *
     * @access public
     * @param Mage_Core_Model_Abstract $object
     * @return BroSolutions_CustomBackground_Model_Resource_Background
     * @author Ultimate Module Creator
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());
            $object->setData('store_id', $stores);
        }
        return parent::_afterLoad($object);
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param BroSolutions_CustomBackground_Model_Background $object
     * @return Zend_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);
        if ($object->getStoreId()) {
            $storeIds = array(Mage_Core_Model_App::ADMIN_STORE_ID, (int)$object->getStoreId());
            $select->join(
                array('custombackground_background_store' => $this->getTable('brosolutions_custombackground/background_store')),
                $this->getMainTable() . '.entity_id = custombackground_background_store.background_id',
                array()
            )
            ->where('custombackground_background_store.store_id IN (?)', $storeIds)
            ->order('custombackground_background_store.store_id DESC')
            ->limit(1);
        }
        return $select;
    }

    /**
     * Assign background to store views
     *
     * @access protected
     * @param Mage_Core_Model_Abstract $object
     * @return BroSolutions_CustomBackground_Model_Resource_Background
     * @author Ultimate Module Creator
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $oldStores = $this->lookupStoreIds($object->getId());
        $newStores = (array)$object->getStores();
        if (empty($newStores)) {
            $newStores = (array)$object->getStoreId();
        }
        $table  = $this->getTable('brosolutions_custombackground/background_store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);
        if ($delete) {
            $where = array(
                'background_id = ?' => (int) $object->getId(),
                'store_id IN (?)' => $delete
            );
            $this->_getWriteAdapter()->delete($table, $where);
        }
        if ($insert) {
            $data = array();
            foreach ($insert as $storeId) {
                $data[] = array(
                    'background_id'  => (int) $object->getId(),
                    'store_id' => (int) $storeId
                );
            }
            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }
        return parent::_afterSave($object);
    }
    /**
     * process multiple select fields
     *
     * @access protected
     * @param Mage_Core_Model_Abstract $object
     * @return BroSolutions_CustomBackground_Model_Resource_Background
     * @author Ultimate Module Creator
     */
    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        $targetid = $object->getTargetId();
        if (is_array($targetid)) {
            $object->setTargetId(implode(',', $targetid));
        }
        return parent::_beforeSave($object);
    }
}
