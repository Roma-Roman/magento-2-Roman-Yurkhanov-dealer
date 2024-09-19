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

namespace RomanYurkhanov\Dealer\Block\Dealer\View;

use RomanYurkhanov\Dealer\Block\Dealer\View;

class Reviews extends View
{
    public function getDealerReviews()
    {
        $dealer = $this->getDealer();
        $dealerId = $dealer->getDealerId();
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('dealer_id', $dealerId, 'in')
            ->addFilter('is_confirmed', true)
            ->create();

        return $this->dealerReviewsRepository->getList($searchCriteria)->getItems();
    }
}
