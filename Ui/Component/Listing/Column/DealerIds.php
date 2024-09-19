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

namespace RomanYurkhanov\Dealer\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use RomanYurkhanov\Dealer\Model\DealerRepository;

class DealerIds extends Column
{
    private DealerRepository $dealerRepository;

    public function __construct(
        ContextInterface   $context,
        UiComponentFactory $uiComponentFactory,
        DealerRepository   $dealerRepository,
        array              $components = [],
        array              $data = []
    )
    {
        $this->dealerRepository = $dealerRepository;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $orderId = $item['entity_id'];
                $dealerIds = $this->dealerRepository->getDealerIdsByOrderId($orderId);
                $item[$this->getData('name')] = implode(',', $dealerIds);
            }
        }

        return $dataSource;
    }
}
