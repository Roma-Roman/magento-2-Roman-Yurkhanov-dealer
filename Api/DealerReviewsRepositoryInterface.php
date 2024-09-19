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

namespace RomanYurkhanov\Dealer\Api;

interface DealerReviewsRepositoryInterface
{
    public function save(Data\DealerReviewsInterface $dealerReviews);

    public function getById($dealerReviewsId);

    public function delete(Data\DealerReviewsInterface $dealerReviews);

    public function deleteById($dealerReviewsId);

    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
