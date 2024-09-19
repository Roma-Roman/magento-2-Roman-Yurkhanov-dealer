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

namespace RomanYurkhanov\Dealer\Plugin\Order;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use RomanYurkhanov\Dealer\Api\DealerRepositoryInterface;
use RomanYurkhanov\Dealer\Model\Config;

class OrderRepository
{
    private Config $config;
    private DealerRepositoryInterface $dealerRepository;
    private OrderExtensionFactory $extensionFactory;
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    public function __construct(
        Config                    $config,
        DealerRepositoryInterface $dealerRepository,
        OrderExtensionFactory     $extensionFactory,
        SearchCriteriaBuilder     $searchCriteriaBuilder
    )
    {
        $this->config = $config;
        $this->dealerRepository = $dealerRepository;
        $this->extensionFactory = $extensionFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function afterGet(OrderRepositoryInterface $subject, OrderInterface $order)
    {
        if (!$this->config->isAddDealerInfoEnabled()) {
            return $order;
        }
        if (empty($order->getData('dealer_ids'))) {
            return $order;
        }
        $dealerIds = explode(',', $order->getData('dealer_ids'));

        $dealers = [];
        foreach ($dealerIds as $dealerId) {
            $dealer = $this->dealerRepository->getById($dealerId);
            $dealers[] = $dealer;
        }

        $extensionAttributes = $order->getExtensionAttributes();
        $extensionAttributes = $extensionAttributes ?? $this->extensionFactory->create();
        $extensionAttributes->setDealers($dealers);

        $order->setExtensionAttributes($extensionAttributes);

        return $order;
    }

    public function afterGetList(OrderRepositoryInterface $subject, OrderSearchResultInterface $searchResult)
    {
        if (!$this->config->isAddDealerInfoEnabled()) {
            return $searchResult;
        }

        $orders = $searchResult->getItems();

        foreach ($orders as &$order) {
            if (empty($order->getData('dealer_ids'))) {
                return $searchResult;
            }
            $dealerIds = explode(',', $order->getData('dealer_ids'));

            $dealers = [];
            foreach ($dealerIds as $dealerId) {
                $dealer = $this->dealerRepository->getById($dealerId);
                $dealers[] = $dealer;
            }

            $extensionAttributes = $order->getExtensionAttributes();
            $extensionAttributes = $extensionAttributes ?? $this->extensionFactory->create();
            $extensionAttributes->setDealers($dealers);

            $order->setExtensionAttributes($extensionAttributes);
        }

        return $searchResult;
    }
}
