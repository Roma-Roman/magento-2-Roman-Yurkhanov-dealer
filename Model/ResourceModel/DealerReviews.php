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

namespace RomanYurkhanov\Dealer\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class DealerReviews extends AbstractDb
{
    private const TABLE_NAME = 'romanyurkhanov_dealer_reviews';
    private const PRIMARY_KEY = 'review_id';

    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, self::PRIMARY_KEY);
    }
}
