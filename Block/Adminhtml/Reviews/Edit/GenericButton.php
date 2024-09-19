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

namespace RomanYurkhanov\Dealer\Block\Adminhtml\Reviews\Edit;

use Magento\Backend\Block\Widget\Context;
use RomanYurkhanov\Dealer\Api\DealerReviewsRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class GenericButton
{
    protected Context $context;

    protected DealerReviewsRepositoryInterface $dealerReviewsRepository;

    public function __construct(
        Context                          $context,
        DealerReviewsRepositoryInterface $dealerReviewsRepository
    )
    {
        $this->context = $context;
        $this->dealerReviewsRepository = $dealerReviewsRepository;
    }

    public function getBlockId()
    {
        try {
            return $this->dealerReviewsRepository->getById(
                $this->context->getRequest()->getParam('review_id')
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
