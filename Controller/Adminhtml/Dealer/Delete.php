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
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action\Context;
use RomanYurkhanov\Dealer\Api\DealerRepositoryInterface;
use RomanYurkhanov\Dealer\Model\DealerFactory;
use Magento\Framework\Exception\LocalizedException;

class Delete extends Action implements HttpPostActionInterface
{
    private $dealerRepository;
    private $dealerFactory;
    public function __construct(
        Context                   $context,
        DealerRepositoryInterface $dealerRepository,
        DealerFactory             $dealerFactory
    )
    {
        $this->dealerRepository = $dealerRepository;
        $this->dealerFactory = $dealerFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $model = $this->dealerFactory->create();

        $id = $this->getRequest()->getParam('dealer_id');
        if ($id) {
            try {
                $model = $this->dealerRepository->getById($id);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage(__('This dealer no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
        }

        $model->setData($id);

        try {
            $this->dealerRepository->delete($model);
            $this->messageManager->addSuccessMessage(__('You deleted the dealer.'));
            return $resultRedirect->setPath('*/*/');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $resultRedirect->setPath('*/*/edit', ['dealer_id' => $id]);
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Something went wrong while deleting the dealer.'));
        }

        return $resultRedirect->setPath('*/*/');
    }
}
