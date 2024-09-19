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

namespace RomanYurkhanov\Dealer\Model\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use RomanYurkhanov\Dealer\Model\Config;
use Magento\Framework\Api\SearchCriteriaBuilder;
use RomanYurkhanov\Dealer\Api\DealerRepositoryInterface;

class Dealer extends AbstractSource
{
    protected Config $config;
    protected SearchCriteriaBuilder $searchCriteriaBuilder;
    private DealerRepositoryInterface $dealerRepository;

    public function __construct(
        Config                    $config,
        SearchCriteriaBuilder     $searchCriteriaBuilder,
        DealerRepositoryInterface $dealerRepository
    )
    {
        $this->config = $config;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->dealerRepository = $dealerRepository;
    }

    public function getAllOptions()
    {
        if (!$this->_options) {
            $availableDealers = $this->getAvailableDealersFromConfig();

            $this->_options = [];

            // Add the first empty option(label),
            // for situations when the selected dealer may be disabled in the future
            $this->_options[] = ['value' => '', 'label' => __('-- Please Select a Dealer --')];

            if (!empty($availableDealers)) {
                $searchCriteria = $this->searchCriteriaBuilder
                    ->addFilter('dealer_id', $availableDealers, 'in')
                    ->addFilter('is_enabled', true)
                    ->create();

                $dealerList = $this->dealerRepository->getList($searchCriteria)->getItems();

                foreach ($dealerList as $dealer) {
                    $this->_options[] = ['value' => $dealer->getDealerId(), 'label' => $dealer->getName()];
                }
            }
        }
        return $this->_options;
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
