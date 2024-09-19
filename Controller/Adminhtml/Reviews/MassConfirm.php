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

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use RomanYurkhanov\Dealer\Api\Data\DealerReviewsInterface;
use RomanYurkhanov\Dealer\Api\DealerReviewsRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Exception;

class MassConfirm extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'RomanYurkhanov_Dealer::dealer_reviews';
    protected $dealerReviewsRepository;
    protected SearchCriteriaBuilder $searchCriteriaBuilder;

    public function __construct(
        Context                   $context,
        DealerReviewsRepositoryInterface $dealerReviewsRepository,
        SearchCriteriaBuilder     $searchCriteriaBuilder
    )
    {
        $this->dealerReviewsRepository = $dealerReviewsRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        parent::__construct($context);
    }

    public function execute()
    {
        $dealerReviewsIds = $this->getRequest()->getParam('selected', []);
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('review_id', $dealerReviewsIds, 'in')
            ->create();
        $collection = $this->dealerReviewsRepository->getList($searchCriteria)->getItems();

        $dealerReviewsConfirmed = 0;
        foreach ($collection as $dealerReview) {
            try {
                $dealerReview->setIsConfirmed(DealerReviewsInterface::STATUS_CONFIRMED);
                $this->dealerReviewsRepository->save($dealerReview);
                $dealerReviewsConfirmed++;
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addException(
                    $e,
                    __('Something went wrong while updating status for %1.', $dealerReview->getName())
                );
            }
        }

        if ($dealerReviewsConfirmed) {
            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been confirmed.', $dealerReviewsConfirmed));
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
