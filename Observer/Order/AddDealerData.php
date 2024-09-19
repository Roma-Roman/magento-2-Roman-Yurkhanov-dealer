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

namespace RomanYurkhanov\Dealer\Observer\Order;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\OrderRepositoryInterface;

class AddDealerData implements ObserverInterface
{
    protected OrderRepositoryInterface $orderRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository
    )
    {
        $this->orderRepository = $orderRepository;
    }

    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();

        // Get the order items
        $items = $order->getAllVisibleItems();

        // Initialize the array to store dealers
        $dealers = [];

        // Loop through the items and collect the dealers
        foreach ($items as $item) {
            // Get the custom attribute value from the product
            $dealerId = $item->getProduct()->getData('dealer');

            // Add the dealer to the array
            $dealers[] = $dealerId;
        }

        // Save the dealers data to the order
        $order->setData('dealer_ids', implode(',', $dealers));

        // Save the order
        $this->orderRepository->save($order);
    }
}
