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

use RomanYurkhanov\Dealer\Model\Attribute\Source\Dealer as Source;
use RomanYurkhanov\Dealer\Model\ResourceModel\Dealer\CollectionFactory as DealerCollectionFactory;
use RomanYurkhanov\Dealer\Setup\Patch\Data\AddDefaultDealer;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

use Magento\Catalog\Model\Product\Action as ProductAction;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;

class AddDealerAttribute extends AbstractHelper implements DataPatchInterface
{
    private ModuleDataSetupInterface $moduleDataSetup;
    private EavSetupFactory $eavSetupFactory;
    private EavSetup $eavSetup;

    private CollectionFactory $collectionFactory;
    private ProductAction $productAction;

    private StoreManagerInterface $storeManager;
    private DealerCollectionFactory $dealerCollectionFactory;
    private Source $source;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory          $eavSetupFactory,
        Context                  $context,
        CollectionFactory        $collectionFactory,
        ProductAction            $action,
        StoreManagerInterface    $storeManager,
        Source                   $source,
        DealerCollectionFactory  $dealerCollectionFactory
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->collectionFactory = $collectionFactory;
        $this->productAction = $action;
        $this->storeManager = $storeManager;
        $this->source = $source;
        $this->dealerCollectionFactory = $dealerCollectionFactory;
        parent::__construct($context);
    }

    public function apply()
    {
        $this->eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $defaultDealerId = $this->getDefaultDealer();

        $this->eavSetup->addAttribute('catalog_product', 'dealer', [
            'group' => 'General',
            'type' => 'int',
            'label' => 'Dealer',
            'input' => 'select',
            'source' => Source::class,
            'visible' => true,
            'default' => $defaultDealerId,
            'visible_on_front' => true,
            'filterable' => true,
            'used_in_product_listing' => true,
            'user_defined' => true,
            'required' => true
        ]);

        $collection = $this->collectionFactory->create();
        $storeId = $this->storeManager->getStore()->getId();
        $ids = [];
        foreach ($collection as $item) {
            $ids[] = $item->getEntityId();
        }
        $this->productAction->updateAttributes($ids, array('dealer' => $defaultDealerId), $storeId);

        $this->eavSetup->addAttribute('catalog_product', 'dealer_description', [
            'type' => 'text',
            'label' => 'Dealer description',
            'input' => 'text',
            'default' => '',
            'visible_on_front' => true,
            'used_in_product_listing' => true,
            'required' => false,
        ]);
    }

    public static function getDependencies()
    {
        return [
            AddDefaultDealer::class
        ];
    }

    public function getAliases()
    {
        return [];
    }

    private function getDefaultDealer()
    {
        $collection = $this->dealerCollectionFactory->create();
        $collection->addFieldToFilter('code', 'default_dealer');
        $dealer = $collection->getFirstItem();
        return $dealer->getDealerId();
    }
}
