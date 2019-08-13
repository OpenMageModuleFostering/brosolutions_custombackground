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
 * CustomBackground module install script
 *
 * @category    BroSolutions
 * @package     BroSolutions_CustomBackground
 * @author      Alex (silyadev@gmail.com)
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('brosolutions_custombackground/background'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Background ID'
    )
    ->addColumn(
        'target_type',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
        ),
        'Type Of Object'
    )
    ->addColumn(
        'target_id',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Object'
    )
    ->addColumn(
        'target_element',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(
            'nullable'  => false,
        ),
        'Object'
    )
    ->addColumn(
        'background',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Background'
    )
    ->addColumn(
        'color',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Background Color'
    )        
    ->addColumn(
        'name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Name'
    )
    ->addColumn(
        'repeat_x',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Repeat-X'
    )
    ->addColumn(
        'repeat_y',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Repeat-Y'
    )
    ->addColumn(
        'style',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Additional CSS Styles'
    )
    ->addColumn(
        'status',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Enabled'
    )
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Background Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Background Creation Time'
    ) 
    ->setComment('Background Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('brosolutions_custombackground/background_store'))
    ->addColumn(
        'background_id',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'nullable'  => false,
            'primary'   => true,
        ),
        'Background ID'
    )
    ->addColumn(
        'store_id',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Store ID'
    )
    ->addIndex(
        $this->getIdxName(
            'brosolutions_custombackground/background_store',
            array('store_id')
        ),
        array('store_id')
    )
    ->addForeignKey(
        $this->getFkName(
            'brosolutions_custombackground/background_store',
            'background_id',
            'brosolutions_custombackground/background',
            'entity_id'
        ),
        'background_id',
        $this->getTable('brosolutions_custombackground/background'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'brosolutions_custombackground/background_store',
            'store_id',
            'core/store',
            'store_id'
        ),
        'store_id',
        $this->getTable('core/store'),
        'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Backgrounds To Store Linkage Table');
$this->getConnection()->createTable($table);
$this->endSetup();
