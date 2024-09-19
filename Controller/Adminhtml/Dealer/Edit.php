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

namespace RomanYurkhanov\Dealer\Controller\Adminhtml\Dealer;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use RomanYurkhanov\Dealer\Api\DealerRepositoryInterface;
use Magento\Framework\Controller\ResultFactory;

class Edit extends Action
{
    public $dealerRepository;

    public function __construct(
        Context                   $context,
        DealerRepositoryInterface $dealerRepository
    )
    {
        $this->dealerRepository = $dealerRepository;

        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        if ($this->getRequest()->getParam('dealer_id')) {
            $dealerId = $this->getRequest()->getParam('dealer_id');
            $dealer = $this->dealerRepository->getById($dealerId);
            $resultPage->getConfig()->getTitle()->prepend($dealer->getName());
        } else {
            $title = __('Add New Dealer');
            $resultPage->getConfig()->getTitle()->prepend($title);
        }

        return $resultPage;
    }
}
