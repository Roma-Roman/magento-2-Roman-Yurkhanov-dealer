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

namespace RomanYurkhanov\Dealer\Controller\View\Reviews;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use RomanYurkhanov\Dealer\Api\DealerReviewsRepositoryInterface;
use RomanYurkhanov\Dealer\Model\DealerReviewsFactory;

class Post extends Action implements HttpPostActionInterface
{
    private $request;
    private $dealerReviewsRepository;
    private $dealerReviewsFactory;

    public function __construct(
        Context                          $context,
        RequestInterface                 $request,
        DealerReviewsRepositoryInterface $dealerReviewsRepository,
        DealerReviewsFactory             $dealerReviewsFactory
    )
    {
        $this->request = $request;
        $this->dealerReviewsRepository = $dealerReviewsRepository;
        $this->dealerReviewsFactory = $dealerReviewsFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->request->getPostValue();

        if (!empty($data)) {

            $review = $this->dealerReviewsFactory->create();

            try {
                $review->setDealerId($this->getRequest()->getParam('dealer_id'))
                    ->setTitle($data['title'] ?? '')
                    ->setMessage($data['message'] ?? '')
                    ->setIsConfirmed(false)
                    ->setRate($data['rate'] ?? '');

                $this->dealerReviewsRepository->save($review);

                $this->messageManager->addSuccessMessage(__('You submitted your review for moderation.'));
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('We can\'t post your review right now.'));
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }

        $resultRedirect->setUrl($this->_redirect->getRedirectUrl());
        return $resultRedirect;
    }
}
