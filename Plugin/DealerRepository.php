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

namespace RomanYurkhanov\Dealer\Plugin;

use Magento\Framework\Exception\LocalizedException;
use RomanYurkhanov\Dealer\Api\DealerRepositoryInterface;
use RomanYurkhanov\Dealer\Model\Config;

class DealerRepository
{
    private Config $config;

    public function __construct(
        Config $config
    )
    {
        $this->config = $config;
    }

    public function aroundGetById(
        DealerRepositoryInterface $subject,
        callable                  $proceed,
                                  $dealerId
    )
    {
        if ($this->config->isAddDealerInfoEnabled()) {
            return $proceed($dealerId);
        }

        throw new LocalizedException(
            __('Something went wrong, please check DealerRepository Plugin.')
        );
    }

    public function aroundGetByCode(
        DealerRepositoryInterface $subject,
        callable                  $proceed,
                                  $code
    )
    {
        if ($this->config->isAddDealerInfoEnabled()) {
            return $proceed($code);
        }

        throw new LocalizedException(
            __('Something went wrong, please check DealerRepository Plugin.')
        );
    }

    public function aroundSave(
        DealerRepositoryInterface $subject,
        callable                  $proceed,
                                  $dealer
    )
    {
        if ($this->config->isAddDealerInfoEnabled()) {
            return $proceed($dealer);
        }

        throw new LocalizedException(
            __('Something went wrong, please check DealerRepository Plugin.')
        );
    }

    public function aroundDeleteById(
        DealerRepositoryInterface $subject,
        callable                  $proceed,
                                  $dealerId
    )
    {
        if ($this->config->isAddDealerInfoEnabled()) {
            return $proceed($dealerId);
        }

        throw new LocalizedException(
            __('Something went wrong, please check DealerRepository Plugin.')
        );
    }
}
