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
use Magento\Framework\Controller\Result\JsonFactory;
use RomanYurkhanov\Dealer\Api\DealerRepositoryInterface;

class InlineEdit extends Action
{
    public $jsonFactory;

    public $dealerRepository;

    public function __construct(
        Context                   $context,
        JsonFactory               $jsonFactory,
        DealerRepositoryInterface $dealerRepository
    )
    {
        $this->jsonFactory = $jsonFactory;
        $this->dealerRepository = $dealerRepository;

        parent::__construct($context);
    }

    public function execute()
    {
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];
        if ($this->getRequest()->getParam('isAjax')) {
            $dealerItems = $this->getRequest()->getParam('items', []);
            if ($dealerItems) {
                foreach (array_keys($dealerItems) as $dealerId) {
                    $dealer = $this->dealerRepository->getById($dealerId);
                    $dealer->setData(
                        array_merge($dealer->getData(), $dealerItems[$dealerId])
                    );
                    $this->dealerRepository->save($dealer);
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }
}
