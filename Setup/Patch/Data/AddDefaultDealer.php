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

namespace RomanYurkhanov\Dealer\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

use RomanYurkhanov\Dealer\Model\DealerFactory;
use RomanYurkhanov\Dealer\Model\Dealer;


class AddDefaultDealer implements DataPatchInterface, PatchRevertableInterface
{

    private ModuleDataSetupInterface $moduleDataSetup;
    private DealerFactory $dealerFactory;

    private Dealer $dealer;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        DealerFactory            $dealerFactory
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->dealerFactory = $dealerFactory;
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $this->dealer = $this->dealerFactory->create();

        $this->dealer
            ->setCode('default_dealer')
            ->setName('Default Dealer Name')
            ->setLink('https://www.default-dealer-website.com/')
            ->setLogo(null)
            ->setDescription('Default Dealer Description')
            ->setContactInfo('default_dealer_email_address@gmail.com')
            ->setIsEnabled(1)
            ->save();

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public static function getDependencies()
    {
        return [];
    }

    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $dealerTable = $this->moduleDataSetup->getTable('romanyurkhanov_dealer');

        $this->moduleDataSetup->getConnection()->delete($dealerTable, ['code = ?' => 'default_dealer']);
        $this->moduleDataSetup->getConnection()->endSetup();
    }


    public function getAliases()
    {
        return [];
    }
}
