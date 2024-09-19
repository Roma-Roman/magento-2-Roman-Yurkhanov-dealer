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

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    private ScopeConfigInterface $scopeConfig;


    public function __construct(
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getDealerTabName()
    {
        return $this->scopeConfig->getValue(
            'romanyurkhanov_dealer/general/dealer_tab_name',
            ScopeInterface::SCOPE_STORE
        );
    }

    public function isDealerReviewEnabled()
    {
        return $this->scopeConfig->getValue(
            'romanyurkhanov_dealer/general/dealer_review_enabled',
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getAvailableDealers()
    {
        return $this->scopeConfig->getValue(
            'romanyurkhanov_dealer/advanced_settings/available_dealers',
            ScopeInterface::SCOPE_STORE
        );
    }

    public function isAddDealerInfoEnabled()
    {
        return $this->scopeConfig->getValue(
            'romanyurkhanov_dealer/advanced_settings/add_dealer_info',
            ScopeInterface::SCOPE_STORE
        );
    }
}
