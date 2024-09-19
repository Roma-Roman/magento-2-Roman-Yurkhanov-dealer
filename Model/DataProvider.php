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

use RomanYurkhanov\Dealer\Model\ResourceModel\Dealer\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $loadedData;

    protected $_storeManager;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $dealerCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $dealerCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->_storeManager = $storeManager;
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();
        $mediaUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        foreach ($items as $item) {
            $this->loadedData[$item->getDealerId()] = $item->getData();

            if ($item->getDealerId()) {
                $this->loadedData[$item->getDealerId()]['read_only'] = true;
            } else {
                $this->loadedData[$item->getDealerId()]['read_only'] = false;
            }

            if (isset($this->loadedData[$item->getDealerId()]['logo'])) {
                $name = $this->loadedData[$item->getDealerId()]['logo'];
                unset($this->loadedData[$item->getDealerId()]['logo']);
                $this->loadedData[$item->getDealerId()]['logo'][0] = [
                    'name' => $name,
                    'url' => $mediaUrl . 'romanyurkhanov/dealer/logo/' . $name
                ];
            }
        }

        return $this->loadedData;
    }
}
