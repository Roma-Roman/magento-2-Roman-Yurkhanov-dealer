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

use RomanYurkhanov\Dealer\Model\ResourceModel\Dealer\CollectionFactory as CollectionFactoryDealer;
use RomanYurkhanov\Dealer\Model\ResourceModel\DealerReviews\CollectionFactory;
use RomanYurkhanov\Dealer\Api\DealerRepositoryInterface;

class DataProviderDealerReviewsListing extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $loadedData;

    protected $_storeManager;

    protected DealerRepositoryInterface $dealerRepository;

    protected $collectionDealer;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $dealerReviewsCollectionFactory,
        CollectionFactoryDealer $dealerCollectionFactory,
        DealerRepositoryInterface $dealerRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $dealerReviewsCollectionFactory->create();
        $this->collectionDealer = $dealerCollectionFactory->create();
        $this->dealerRepository = $dealerRepository;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->_storeManager = $storeManager;
    }

    public function getData()
    {
        $items = [];
        $dealerName = [];

        foreach ($this->collection->getItems() as $review) {
            if ($review->getDealerId()) {
                $dealerId = $review->getDealerId();
                $dealer = $this->dealerRepository->getById($dealerId);
                $dealerName = array('dealer_name' => $dealer->getName());
            }
            $dealerReview = array_merge($review->toArray(), $dealerName);
            $items[] = $dealerReview;
        }

        return [
            'totalRecords' => $this->collection->getSize(),
            'items' => $items
        ];
    }
}
