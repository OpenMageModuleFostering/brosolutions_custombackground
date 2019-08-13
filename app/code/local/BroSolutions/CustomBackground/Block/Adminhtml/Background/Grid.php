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
 * Background admin grid block
 *
 * @category    BroSolutions
 * @package     BroSolutions_CustomBackground
 * @author      Alex (silyadev@gmail.com)
 */
class BroSolutions_CustomBackground_Block_Adminhtml_Background_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('backgroundGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BroSolutions_CustomBackground_Block_Adminhtml_Background_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('brosolutions_custombackground/background')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BroSolutions_CustomBackground_Block_Adminhtml_Background_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('brosolutions_custombackground')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'name',
            array(
                'header'    => Mage::helper('brosolutions_custombackground')->__('Name'),
                'align'     => 'left',
                'index'     => 'name',
            )
        );
        
        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('brosolutions_custombackground')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('brosolutions_custombackground')->__('Enabled'),
                    '0' => Mage::helper('brosolutions_custombackground')->__('Disabled'),
                )
            )
        );
        $this->addColumn(
            'target_type',
            array(
                'header' => Mage::helper('brosolutions_custombackground')->__('Type Of Object'),
                'index'  => 'target_type',
                'type'  => 'options',
                'options' => Mage::helper('brosolutions_custombackground')->convertOptions(
                    Mage::getModel('brosolutions_custombackground/background_attribute_source_targettype')->getAllOptions(false)
                )

            )
        );
        if (!Mage::app()->isSingleStoreMode() && !$this->_isExport) {
            $this->addColumn(
                'store_id',
                array(
                    'header'     => Mage::helper('brosolutions_custombackground')->__('Store Views'),
                    'index'      => 'store_id',
                    'type'       => 'store',
                    'store_all'  => true,
                    'store_view' => true,
                    'sortable'   => false,
                    'filter_condition_callback'=> array($this, '_filterStoreCondition'),
                )
            );
        }
        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('brosolutions_custombackground')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('brosolutions_custombackground')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('brosolutions_custombackground')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('brosolutions_custombackground')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('brosolutions_custombackground')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BroSolutions_CustomBackground_Block_Adminhtml_Background_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('background');
        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'=> Mage::helper('brosolutions_custombackground')->__('Delete'),
                'url'  => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('brosolutions_custombackground')->__('Are you sure?')
            )
        );
        $this->getMassactionBlock()->addItem(
            'status',
            array(
                'label'      => Mage::helper('brosolutions_custombackground')->__('Change status'),
                'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                    'status' => array(
                        'name'   => 'status',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('brosolutions_custombackground')->__('Status'),
                        'values' => array(
                            '1' => Mage::helper('brosolutions_custombackground')->__('Enabled'),
                            '0' => Mage::helper('brosolutions_custombackground')->__('Disabled'),
                        )
                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'target_type',
            array(
                'label'      => Mage::helper('brosolutions_custombackground')->__('Change Type Of Object'),
                'url'        => $this->getUrl('*/*/massTargetType', array('_current'=>true)),
                'additional' => array(
                    'flag_target_type' => array(
                        'name'   => 'flag_target_type',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('brosolutions_custombackground')->__('Type Of Object'),
                        'values' => Mage::getModel('brosolutions_custombackground/background_attribute_source_targettype')
                            ->getAllOptions(true),

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'repeat_x',
            array(
                'label'      => Mage::helper('brosolutions_custombackground')->__('Change Repeat-X'),
                'url'        => $this->getUrl('*/*/massRepeatX', array('_current'=>true)),
                'additional' => array(
                    'flag_repeat_x' => array(
                        'name'   => 'flag_repeat_x',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('brosolutions_custombackground')->__('Repeat-X'),
                        'values' => array(
                                '1' => Mage::helper('brosolutions_custombackground')->__('Yes'),
                                '0' => Mage::helper('brosolutions_custombackground')->__('No'),
                            )

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'repeat_y',
            array(
                'label'      => Mage::helper('brosolutions_custombackground')->__('Change Repeat-Y'),
                'url'        => $this->getUrl('*/*/massRepeatY', array('_current'=>true)),
                'additional' => array(
                    'flag_repeat_y' => array(
                        'name'   => 'flag_repeat_y',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('brosolutions_custombackground')->__('Repeat-Y'),
                        'values' => array(
                                '1' => Mage::helper('brosolutions_custombackground')->__('Yes'),
                                '0' => Mage::helper('brosolutions_custombackground')->__('No'),
                            )

                    )
                )
            )
        );
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param BroSolutions_CustomBackground_Model_Background
     * @return string
     * @author Ultimate Module Creator
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * get the grid url
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    /**
     * after collection load
     *
     * @access protected
     * @return BroSolutions_CustomBackground_Block_Adminhtml_Background_Grid
     * @author Ultimate Module Creator
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    /**
     * filter store column
     *
     * @access protected
     * @param BroSolutions_CustomBackground_Model_Resource_Background_Collection $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return BroSolutions_CustomBackground_Block_Adminhtml_Background_Grid
     * @author Ultimate Module Creator
     */
    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->addStoreFilter($value);
        return $this;
    }
}
