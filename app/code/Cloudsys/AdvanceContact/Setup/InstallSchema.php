<?php 
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cloudsys\AdvanceContact\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 *  InstallSchema - Create table
 *
 *@package Cloudsys\AdvanceContact\Setup 
 */
class InstallSchema implements InstallSchemaInterface
{
   
  public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
  {
      $installer = $setup;

      $installer->startSetup();

    /*
     * table "cloudsys_contact"
     * table fields
     *
     */
      $table = $installer->getConnection()->newTable($installer->getTable('cloudsys_contact'))
          ->addColumn(
              'id',
              Table::TYPE_INTEGER,
              null,
              ['identity' => true, 'nullable' => false, 'primary' => true],
              'Contact ID'
          )->addColumn(
            'firstname',
            Table::TYPE_TEXT,
            100,
            ['nullable' => false, 'default' => ''],
            'User Firstname'
          )->addColumn(
            'lastname',
            Table::TYPE_TEXT,
            100,
            ['nullable' => false, 'default' => ''],
            'User Latname'
          )->addColumn(
            'email',
            Table::TYPE_TEXT,
            100,
            ['nullable' => false, 'default' => ''],
            'User email'
          )->addColumn(
            'phone_no',
            Table::TYPE_TEXT,
            100,
            ['nullable' => false, 'default' => ''],
            'User Phone Number'
          )->addColumn(
              'title',
              Table::TYPE_TEXT,
              255,
              ['nullable' => false,'default' => ''],
              'User contact Title'
          )->addColumn(
            'company_name',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false, 'default' => ''],
            'User Company name'
          )->addColumn(
            'industry_served',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false, 'default' => ''],
            'Industry Served'
          )->addColumn(
            'message',
            Table::TYPE_TEXT,
            '2M',
            ['nullable' => false, 'default' => ''],
            'User message'
          )->addColumn(
              'status',
              Table::TYPE_SMALLINT,
              1,
              ['nullable' => false]
          )->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
             null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
            'Created At'
        )
        ->addColumn(
            'updated_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
            'Updated At'
        )
        ->setComment('Cloudsys User contact-us deatils');
        $installer->getConnection()->createTable($table);
        
        $installer->endSetup();
    }

}