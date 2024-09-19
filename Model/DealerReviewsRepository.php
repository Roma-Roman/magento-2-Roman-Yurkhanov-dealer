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

namespace RomanYurkhanov\Dealer\Model;

use RomanYurkhanov\Dealer\Api\DealerReviewsRepositoryInterface;
use RomanYurkhanov\Dealer\Api\Data\DealerReviewsInterface;
use RomanYurkhanov\Dealer\Api\Data\DealerReviewsInterfaceFactory;
use RomanYurkhanov\Dealer\Api\Data\DealerReviewsSearchResultInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use RomanYurkhanov\Dealer\Api\Data\DealerReviewsSearchResultInterfaceFactory;
use RomanYurkhanov\Dealer\Model\ResourceModel\DealerReviews as DealerReviewsResourceModel;
use RomanYurkhanov\Dealer\Model\ResourceModel\DealerReviews\CollectionFactory as DealerReviewsCollectionFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;

class DealerReviewsRepository implements DealerReviewsRepositoryInterface
{
    private DealerReviewsResourceModel $resource;

    private DealerReviewsInterfaceFactory $dealerReviewsInterfaceFactory;

    private DealerReviewsCollectionFactory $dealerReviewsCollectionFactory;

    private SearchResultsInterfaceFactory $searchResultsFactory;

    private JoinProcessorInterface $extensionAttributesJoinProcessor;

    private CollectionProcessorInterface $collectionProcessor;

    private array $dealerReviewsInstances = [];

    public function __construct(
        DealerReviewsResourceModel $resource,
        DealerReviewsInterfaceFactory $dealerReviewsInterfaceFactory,
        DealerReviewsCollectionFactory $dealerReviewsCollectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->dealerReviewsInterfaceFactory = $dealerReviewsInterfaceFactory;
        $this->dealerReviewsCollectionFactory = $dealerReviewsCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->collectionProcessor = $collectionProcessor;
    }

    public function save(DealerReviewsInterface $dealerReviews) : DealerReviewsInterface
    {
        try {
            $this->resource->save($dealerReviews);
            $this->dealerReviewsInstances[$dealerReviews->getId()] = $dealerReviews;
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $dealerReviews;
    }

    public function getById($dealerReviewsId, $isForceLoadEnabled = false)
    {
        if ($isForceLoadEnabled || !isset($this->dealerReviewsInstances[$dealerReviewsId])) {
            $dealerReviews = $this->dealerReviewsInterfaceFactory->create();
            $this->resource->load($dealerReviews, $dealerReviewsId);
            if (!$dealerReviews->getId()) {
                throw NoSuchEntityException::singleField('review_id', $dealerReviewsId);
            }
            $this->dealerReviewsInstances[$dealerReviewsId] = $dealerReviews;
        }
        return $this->dealerReviewsInstances[$dealerReviewsId];
    }

    public function delete(DealerReviewsInterface $dealerReviews) : bool
    {
        try {
            $this->resource->delete($dealerReviews);
            unset($this->dealerReviewsInstances[$dealerReviews->getId()]);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;
    }

    public function deleteById($dealerReviewsId) : bool
    {
        $review = $this->getById($dealerReviewsId);
        return $this->delete($review);
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->dealerReviewsCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setItems($collection->getItems());
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }
}
