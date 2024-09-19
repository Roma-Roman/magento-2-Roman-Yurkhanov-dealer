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

namespace RomanYurkhanov\Dealer\Block\Dealer;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use RomanYurkhanov\Dealer\Api\DealerRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use RomanYurkhanov\Dealer\Model\Config;

class All extends Template
{
    private Config $config;

    private DealerRepositoryInterface $dealerRepository;

    protected SearchCriteriaBuilder $searchCriteriaBuilder;

    public function __construct(
        Context                   $context,
        Config                    $config,
        DealerRepositoryInterface $dealerRepository,
        SearchCriteriaBuilder     $searchCriteriaBuilder,
        array                     $data = []
    )
    {
        $this->dealerRepository = $dealerRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->config = $config;
        parent::__construct($context, $data);
    }

    public function getDealers() :array
    {
        $availableDealers = $this->getAvailableDealersFromConfig();

        if (!empty($availableDealers)) {
            $searchCriteria = $this->searchCriteriaBuilder
                ->addFilter('dealer_id', $availableDealers, 'in')
                ->addFilter('is_enabled', true)
                ->create();
            return $this->dealerRepository->getList($searchCriteria)->getItems();
        }
        return [];
    }

    /**
     * Retrieve available dealers from the configuration
     *
     * @return array
     */
    private function getAvailableDealersFromConfig(): array
    {
        // Get the selected dealer IDs from the configuration
        $availableDealers = $this->config->getAvailableDealers();

        // Return as an array of dealer IDs
        return $availableDealers ? explode(',', $availableDealers) : [];
    }
}
