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

namespace RomanYurkhanov\Dealer\Model\Config;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use RomanYurkhanov\Dealer\Api\DealerRepositoryInterface;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class Dealer extends AbstractSource
{
    private DealerRepositoryInterface $dealerRepository;

    protected SearchCriteriaBuilder $searchCriteriaBuilder;
    protected SortOrderBuilder $sortOrderBuilder;

    public function __construct(
        DealerRepositoryInterface $dealerRepository,
        SearchCriteriaBuilder     $searchCriteriaBuilder,
        SortOrderBuilder          $sortOrderBuilder
    )
    {
        $this->dealerRepository = $dealerRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
    }

    public function getAllOptions()
    {
        if (!$this->_options) {
            $searchCriteria = $this->searchCriteriaBuilder->create();
            $collection = $this->dealerRepository->getList($searchCriteria)->getItems();
            $this->_options = [];
            foreach ($collection as $item) {
                $this->_options[] = ['value' => $item->getDealerId(), 'label' => $item->getName()];
            }
        }
        return $this->_options;
    }
}
