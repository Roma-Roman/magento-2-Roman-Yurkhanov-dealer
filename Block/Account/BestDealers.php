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

namespace RomanYurkhanov\Dealer\Block\Account;

use Magento\Customer\Model\Session;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Sales\Model\Order\Config;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use RomanYurkhanov\Dealer\Api\DealerRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;

class BestDealers extends Template
{
    const ORDER_LIMIT = 5;
    protected CollectionFactoryInterface $_orderCollectionFactory;
    protected Session $_customerSession;
    protected Config $_orderConfig;
    private StoreManagerInterface $storeManager;

    protected DealerRepositoryInterface $dealerRepository;
    protected SearchCriteriaBuilder $searchCriteriaBuilder;
    protected CollectionFactoryInterface $orderFactory;
    protected SortOrderBuilder $sortOrderBuilder;


    public function __construct(
        Context                    $context,
        CollectionFactoryInterface $orderCollectionFactory,
        Session                    $customerSession,
        Config                     $orderConfig,
        DealerRepositoryInterface  $dealerRepository,
        SearchCriteriaBuilder      $searchCriteriaBuilder,
        SortOrderBuilder           $sortOrderBuilder,
        array                      $data = [],
        StoreManagerInterface      $storeManager = null

    )
    {
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_customerSession = $customerSession;
        $this->_orderConfig = $orderConfig;
        $this->_isScopePrivate = true;
        $this->storeManager = $storeManager ?: ObjectManager::getInstance()
            ->get(StoreManagerInterface::class);
        $this->dealerRepository = $dealerRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
        parent::__construct($context, $data);
    }


    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        parent::_construct();
        $this->getRecentOrders();
    }

    public function getBestDealers()
    {
        $dealerQuantities = [];
        $orders = $this->getOrders();

        foreach ($orders as $order) {
            $items = $order->getAllVisibleItems();

            foreach ($items as $item) {
                $dealerId = $item->getProduct()->getData('dealer');

                if (!isset($dealerQuantities[$dealerId])) {
                    $dealerQuantities[$dealerId] = 0;
                }

                $qty = $item->getQtyOrdered();
                $dealerQuantities[$dealerId] += $qty;
            }
        }

        arsort($dealerQuantities);

        $sortedDealerIds = array_keys($dealerQuantities);

        $dealers = [];
        foreach ($sortedDealerIds as $dealerId) {
            $dealers[] = $this->dealerRepository->getById($dealerId);
        }

        return $dealers;
    }

    /**
     * Get all placed and complete orders. For Dealers list
     */
    private function getOrders()
    {
        $customerId = $this->_customerSession->getCustomerId();
        $orders = $this->_orderCollectionFactory->create($customerId)->addAttributeToSelect(
            '*'
        )->addAttributeToFilter(
            'customer_id',
            $customerId
        )->addAttributeToFilter(
            'store_id',
            $this->storeManager->getStore()->getId()
        )->addAttributeToFilter(
            'status',
            ['eq' => \Magento\Sales\Model\Order::STATE_COMPLETE]
        )->addAttributeToSort(
            'created_at',
            'desc'
        )->load();

        return $orders;
    }

    /**
     * Get all placed and complete orders. For Orders table
     */
    private function getRecentOrders()
    {
        $customerId = $this->_customerSession->getCustomerId();
        $orders = $this->_orderCollectionFactory->create($customerId)->addAttributeToSelect(
            '*'
        )->addAttributeToFilter(
            'customer_id',
            $customerId
        )->addAttributeToFilter(
            'store_id',
            $this->storeManager->getStore()->getId()
        )->addAttributeToFilter(
            'status',
            ['eq' => \Magento\Sales\Model\Order::STATE_COMPLETE]
        )->addAttributeToSort(
            'created_at',
            'desc'
//        )->setPageSize(
//            self::ORDER_LIMIT
        )->load();
        $this->setOrders($orders);
    }

    /**
     * Get order view URL
     *
     * @param object $order
     * @return string
     */
    public function getViewUrl($order)
    {
        return $this->getUrl('sales/order/view', ['order_id' => $order->getId()]);
    }

    /**
     * Get reorder URL
     *
     * @param object $order
     * @return string
     */
    public function getReorderUrl($order)
    {
        return $this->getUrl('sales/order/reorder', ['order_id' => $order->getId()]);
    }
}
