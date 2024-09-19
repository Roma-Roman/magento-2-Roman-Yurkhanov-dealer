<?php
/**
 * Roman Yurkhanov
 *
 * Email :: ferrumdp@gmail.com
 * Linkedin :: https://www.linkedin.com/in/roman-yurkhanov-322035122/
 *
 * Copyright 2024-present Roman Yurkhanov. All rights reserved.
 * See license.txt for license details.
 */

namespace RomanYurkhanov\Dealer\Setup\Patch\Schema;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class AddDealerIdsColumns implements SchemaPatchInterface, PatchRevertableInterface
{
    private SchemaSetupInterface $schemaSetup;

    public function __construct(
        SchemaSetupInterface $schemaSetup
    )
    {
        $this->schemaSetup = $schemaSetup;
    }

    public function apply()
    {
        $this->schemaSetup->getConnection()->startSetup();

        $this->schemaSetup->getConnection()->addColumn(
            $this->schemaSetup->getTable('sales_order'),
            'dealer_ids',
            [
                'type' => Table::TYPE_TEXT,
                'length'   => 255,
                'nullable' => true,
                'comment' => 'Dealer Ids from Ordered Products'
            ]
        );

        $this->schemaSetup->getConnection()->addColumn(
            $this->schemaSetup->getTable('sales_order_grid'),
            'dealer_ids',
            [
                'type' => Table::TYPE_TEXT,
                'length'   => 255,
                'nullable' => true,
                'comment' => 'Dealer Ids from Ordered Products'
            ]
        );

        $this->schemaSetup->getConnection()->endSetup();
    }

    public static function getDependencies()
    {
        return [];
    }

    public function revert()
    {
        $this->schemaSetup->getConnection()->startSetup();

        $this->schemaSetup->getConnection()->dropColumn(
            $this->schemaSetup->getTable('sales_order'),
            'dealer_ids'
        );

        $this->schemaSetup->getConnection()->dropColumn(
            $this->schemaSetup->getTable('sales_order_grid'),
            'dealer_ids'
        );

        $this->schemaSetup->getConnection()->endSetup();
    }


    public function getAliases()
    {
        return [];
    }
}
