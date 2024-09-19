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

use RomanYurkhanov\Dealer\Api\DealerRepositoryInterface;
use RomanYurkhanov\Dealer\Api\Data\DealerInterface;
use RomanYurkhanov\Dealer\Api\Data\DealerInterfaceFactory;
use RomanYurkhanov\Dealer\Api\Data\DealerSearchResultInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use RomanYurkhanov\Dealer\Api\Data\DealerSearchResultInterfaceFactory;
use RomanYurkhanov\Dealer\Model\ResourceModel\Dealer as DealerResourceModel;
use RomanYurkhanov\Dealer\Model\ResourceModel\Dealer\CollectionFactory as DealerCollectionFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\OrderRepositoryInterface;

class DealerRepository implements DealerRepositoryInterface
{
    private DealerResourceModel $resource;

    private DealerInterfaceFactory $dealerInterfaceFactory;

    private DealerCollectionFactory $dealerCollectionFactory;

    private SearchResultsInterfaceFactory $searchResultsFactory;

    private JoinProcessorInterface $extensionAttributesJoinProcessor;

    private CollectionProcessorInterface $collectionProcessor;

    private OrderRepositoryInterface $orderRepository;

    private array $dealerInstances = [];

    public function __construct(
        DealerResourceModel           $resource,
        DealerInterfaceFactory        $dealerInterfaceFactory,
        DealerCollectionFactory       $dealerCollectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory,
        JoinProcessorInterface        $extensionAttributesJoinProcessor,
        CollectionProcessorInterface  $collectionProcessor,
        OrderRepositoryInterface      $orderRepository
    )
    {
        $this->resource = $resource;
        $this->dealerInterfaceFactory = $dealerInterfaceFactory;
        $this->dealerCollectionFactory = $dealerCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->collectionProcessor = $collectionProcessor;
        $this->orderRepository = $orderRepository;
    }

    public function save(DealerInterface $dealer): DealerInterface
    {
        try {
            $this->resource->save($dealer);
            $this->dealerInstances[$dealer->getId()] = $dealer;
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $dealer;
    }

    public function getById($dealerId, $isForceLoadEnabled = false)
    {
        if ($isForceLoadEnabled || !isset($this->dealerInstances[$dealerId])) {
            $dealer= $this->dealerInterfaceFactory->create();
            $this->resource->load($dealer, $dealerId);
            if (!$dealer->getId()) {
                throw NoSuchEntityException::singleField('dealer_id', $dealerId);
            }
            $this->dealerInstances[$dealerId] = $dealer;
        }
        return $this->dealerInstances[$dealerId];
    }

    public function getByCode($code, $isForceLoadEnabled = false)
    {
        if ($isForceLoadEnabled || !isset($this->dealerInstances[$code])) {
            $dealer = $this->dealerInterfaceFactory->create();
            $this->resource->load($dealer, $code, 'code');
            if (!$dealer->getCode()) {
                throw NoSuchEntityException::singleField('code', $code);
            }
            $this->dealerInstances[$code] = $dealer;
        }
        return $this->dealerInstances[$code];
    }

    public function delete(DealerInterface $dealer): bool
    {
        try {
            $this->resource->delete($dealer);
            unset($this->dealerInstances[$dealer->getId()]);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;
    }

    public function deleteById($dealerId): bool
    {
        $dealer = $this->getById($dealerId);
        return $this->delete($dealer);
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->dealerCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setItems($collection->getItems());
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    public function getDealerIdsByOrderId($orderId): array
    {
        $dealerIds = [];

        try {
            $order = $this->orderRepository->get($orderId);
            $dealerIdsString = $order->getData('dealer_ids');

            // Check if the returned value is not null or empty before calling explode
            if ($dealerIdsString !== null && $dealerIdsString !== '') {
                $dealerIds = explode(',', $dealerIdsString);
            }
        } catch (LocalizedException $e) {
            // Handle exception if needed
        }

        return $dealerIds;
    }
}
