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

namespace RomanYurkhanov\Dealer\Block\Adminhtml\Dealer\Edit;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use RomanYurkhanov\Dealer\Api\DealerRepositoryInterface;

class GenericButton
{
    protected Context $context;

    protected DealerRepositoryInterface $dealerRepository;

    public function __construct(
        Context                   $context,
        DealerRepositoryInterface $dealerRepository
    )
    {
        $this->context = $context;
        $this->dealerRepository = $dealerRepository;
    }

    public function getBlockId()
    {
        try {
            return $this->dealerRepository->getById(
                $this->context->getRequest()->getParam('dealer_id')
            )->getId();
        } catch (NoSuchEntityException $e) {
        }
        return null;
    }

    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
