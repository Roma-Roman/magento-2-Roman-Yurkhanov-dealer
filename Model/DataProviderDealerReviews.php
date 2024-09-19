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

use RomanYurkhanov\Dealer\Model\ResourceModel\DealerReviews\CollectionFactory;
use RomanYurkhanov\Dealer\Api\DealerRepositoryInterface;

class DataProviderDealerReviews extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $loadedData;

    protected $_storeManager;

    protected $dealerRepository;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $dealerReviewsCollectionFactory,
        DealerRepositoryInterface $dealerRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $dealerReviewsCollectionFactory->create();
        $this->dealerRepository = $dealerRepository;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->_storeManager = $storeManager;
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();

        foreach ($items as $item) {
            $this->loadedData[$item->getReviewId()] = $item->getData();

            if ($item->getReviewId()) {
                $this->loadedData[$item->getReviewId()]['read_only'] = true;
            } else {
                $this->loadedData[$item->getReviewId()]['read_only'] = false;
            }

            if ($item->getDealerId()) {
                $dealerId = $item->getDealerId();
                $dealer = $this->dealerRepository->getById($dealerId);
                $dealerName = $dealer->getName();
                $this->loadedData[$item->getReviewId()]['dealer_name'] = $dealerName;
            }
        }

        return $this->loadedData;
    }
}
