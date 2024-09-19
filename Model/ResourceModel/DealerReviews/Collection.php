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

namespace RomanYurkhanov\Dealer\Model\ResourceModel\DealerReviews;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use RomanYurkhanov\Dealer\Model\DealerReviews;
use RomanYurkhanov\Dealer\Model\ResourceModel\DealerReviews as DealerReviewsResource;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(DealerReviews::class, DealerReviewsResource::class);

    }
}
