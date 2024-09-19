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
use Magento\Backend\App\Action\Context;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Controller\ResultFactory;
use RomanYurkhanov\Dealer\Api\DealerReviewsRepositoryInterface;
use RomanYurkhanov\Dealer\Api\DealerRepositoryInterface;

class Edit extends Action
{
    protected DealerRepositoryInterface $dealerRepository;
    protected DealerReviewsRepositoryInterface $dealerReviewsRepository;
    protected SearchCriteriaBuilder $searchCriteriaBuilder;
    protected FilterBuilder $filterBuilder;

    public function __construct(
        Context                          $context,
        DealerRepositoryInterface        $dealerRepository,
        DealerReviewsRepositoryInterface $dealerReviewsRepository,
        SearchCriteriaBuilder            $searchCriteriaBuilder,
        FilterBuilder                    $filterBuilder
    )
    {
        $this->dealerRepository = $dealerRepository;
        $this->dealerReviewsRepository = $dealerReviewsRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;

        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $reviewId = $this->getRequest()->getParam('review_id');

        $filters[] = $this->filterBuilder
            ->setField('review_id')
            ->setConditionType('eq')
            ->setValue($reviewId)
            ->create();

        $this->searchCriteriaBuilder->addFilters($filters);

        $searchCriteria = $this->searchCriteriaBuilder->create();
        $searchCriteria->setPageSize(1)->setCurrentPage(1);

        $dealerList = $this->dealerReviewsRepository->getList($searchCriteria);
        $dealerItem = current($dealerList->getItems());
        $dealerId = $dealerItem->getDealerId();
        $dealer = $this->dealerRepository->getById($dealerId);
        $dealerName = $dealer->getName();

        $title = __('Review About Dealer - ') . $dealerName;
        $resultPage->getConfig()->getTitle()->prepend($title);

        return $resultPage;
    }
}
