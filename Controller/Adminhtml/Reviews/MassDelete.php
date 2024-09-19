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
use RomanYurkhanov\Dealer\Api\DealerReviewsRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Exception;

class MassDelete extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'RomanYurkhanov_Dealer::dealer_reviews';
    protected $dealerReviewsRepository;
    protected SearchCriteriaBuilder $searchCriteriaBuilder;

    public function __construct(
        Context                          $context,
        DealerReviewsRepositoryInterface $dealerReviewsRepository,
        SearchCriteriaBuilder            $searchCriteriaBuilder
    )
    {
        $this->dealerReviewsRepository = $dealerReviewsRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        parent::__construct($context);
    }

    public function execute()
    {
        $reviewIds = $this->getRequest()->getParam('selected', []);
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('review_id', $reviewIds, 'in')
            ->create();
        $collection = $this->dealerReviewsRepository->getList($searchCriteria)->getItems();

        $reviewDeleted = 0;
        foreach ($collection as $review) {
            try {
                $this->dealerReviewsRepository->delete($review);
                $reviewDeleted++;
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addException(
                    $e,
                    __('Something went wrong while deleting %1.', $review->getTitle())
                );
            }
        }

        if ($reviewDeleted) {
            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $reviewDeleted));
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
