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

namespace RomanYurkhanov\Dealer\Plugin\Catalog;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\Data\ProductSearchResultsInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use RomanYurkhanov\Dealer\Api\DealerRepositoryInterface;
use RomanYurkhanov\Dealer\Model\Config;

class ProductRepository
{
    private Config $config;
    private DealerRepositoryInterface $dealerRepository;

    public function __construct(
        Config                    $config,
        DealerRepositoryInterface $dealerRepository
    )
    {
        $this->config = $config;
        $this->dealerRepository = $dealerRepository;
    }

    public function afterGetById
    (
        ProductRepositoryInterface $subject,
        ProductInterface           $product
    )
    {
        if (!$this->config->isAddDealerInfoEnabled()) {
            return $product;
        }
        $attributes = $product->getExtensionAttributes();
        $dealerId = $product->getData('dealer');

        $dealer = $this->dealerRepository->getById($dealerId);

        $attributes->setDealer($dealer);
        $product->setExtensionAttributes($attributes);

        return $product;
    }

    public function afterGetList(
        ProductRepositoryInterface    $subject,
        ProductSearchResultsInterface $searchResult,
        SearchCriteriaInterface       $searchCriteria
    )
    {
        if (!$this->config->isAddDealerInfoEnabled()) {
            return $searchResult;
        }

        $products = $searchResult->getItems();
        foreach ($products as $product) {
            $attributes = $product->getExtensionAttributes();
            $dealerId = $product->getData('dealer');

            $dealer = $this->dealerRepository->getById($dealerId);

            $attributes->setDealer($dealer);
            $product->setExtensionAttributes($attributes);
        }

        return $searchResult;
    }
}
