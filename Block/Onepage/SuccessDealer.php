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

namespace RomanYurkhanov\Dealer\Block\Onepage;

use Magento\Checkout\Block\Onepage\Success;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Checkout\Model\Session;
use Magento\Sales\Model\Order\Config;
use Magento\Framework\App\Http\Context as httpContext;

class SuccessDealer extends Success
{
    protected OrderRepositoryInterface $orderRepository;

    public function __construct(
        Context                  $context,
        Session                  $checkoutSession,
        Config                   $orderConfig,
        httpContext              $httpContext,
        OrderRepositoryInterface $orderRepository,
        array                    $data = []

    )
    {
        parent::__construct($context, $checkoutSession, $orderConfig, $httpContext, $data);
        $this->orderRepository = $orderRepository;
    }

    public function getOrderItems()
    {
        $orderId = $this->getOrderId();
        $order = $this->orderRepository->get($orderId);
        $orderItems = $order->getAllVisibleItems();

        return $orderItems;
    }
}
