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

namespace RomanYurkhanov\Dealer\Controller\Adminhtml\Reviews;

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action\Context;
use RomanYurkhanov\Dealer\Api\Data\DealerReviewsInterface;
use RomanYurkhanov\Dealer\Api\DealerReviewsRepositoryInterface;
use RomanYurkhanov\Dealer\Model\DealerReviewsFactory;
use Magento\Framework\Exception\LocalizedException;

class Save extends Action implements HttpPostActionInterface
{
    protected $dataPersistor;

    private $dealerReviewsRepository;

    private $dealerReviewsFactory;

    public function __construct(
        Context                          $context,
        DealerReviewsRepositoryInterface $dealerReviewsRepository,
        DealerReviewsFactory             $dealerReviewsFactory
    )
    {
        $this->dealerReviewsRepository = $dealerReviewsRepository;
        $this->dealerReviewsFactory = $dealerReviewsFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $id = $this->getRequest()->getParam('review_id');
            if ($id) {
                try {
                    $model = $this->dealerReviewsRepository->getById($id);

                    if (!$model->getIsConfirmed()) {
                        $model->setIsConfirmed(DealerReviewsInterface::STATUS_CONFIRMED);
                        $this->dealerReviewsRepository->save($model);
                        $this->messageManager->addSuccessMessage(__('You confirmed the review.'));
                    } else {
                        $this->messageManager->addSuccessMessage(__('Review is already confirmed.'));
                    }
                } catch (LocalizedException $e) {
                    $this->messageManager->addExceptionMessage($e, __('Something went wrong while confirming the review.'));
                }
            }
        }
        return $resultRedirect->setPath('*/*/');
    }
}
