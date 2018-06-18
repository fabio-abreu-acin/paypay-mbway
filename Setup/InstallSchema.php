<?php

namespace Paypay\Multibanco\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    /**
     * install tables
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     * @return void
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if (!$installer->tableExists('paypay_payment')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('paypay_payment')
            )
            ->addColumn(
                'id_order',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ],
                'ORDER ID'
            )
			->addColumn(
                'id_payment_paypay',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                255,
                [],
                'PAYPAY PAYMENT ID'
            )
            ->addColumn(
                'entity',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'MB ENTITY'
            )
            ->addColumn(
                'amount',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'AMOUNT'
            )
            ->addColumn(
                'reference',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [],
                'REFERENCE'
            )
            ->addColumn(
                'paypayLink',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                512,
                [],
                'PAYPAY LINK'
            )
            ->addColumn(
                'token',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                512,
                [],
                'TOKEN'
            )
            ->setComment('paypay_payment Table');
            $installer->getConnection()->createTable($table);

            $installer->getConnection()->addIndex(
                $installer->getTable('paypay_payment'),
                $setup->getIdxName(
                    $installer->getTable('paypay_payment'),
                    ['id_order','id_payment_paypay','reference','paypayLink','token'],
                    \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                ['id_order','id_payment_paypay','reference','paypayLink','token'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
            );
        }

        $installer->endSetup();
    }
}