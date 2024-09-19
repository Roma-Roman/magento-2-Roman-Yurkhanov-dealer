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

namespace RomanYurkhanov\Dealer\Model\Product\View\Dealer;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use RomanYurkhanov\Dealer\Model\Config;

class Tab implements ArgumentInterface
{
    protected Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function __toString()
    {
        return $this->config->getDealerTabName();
    }
}
