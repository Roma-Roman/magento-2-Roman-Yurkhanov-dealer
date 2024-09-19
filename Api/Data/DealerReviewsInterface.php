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

namespace RomanYurkhanov\Dealer\Api\Data;

interface DealerReviewsInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    const REVIEW_ID = 'review_id';
    const DEALER_ID = 'dealer_id';
    const RATE = 'rate';
    const TITLE = 'title';
    const MESSAGE = 'message';
    const IS_CONFIRMED = 'is_confirmed';
    const STATUS_CONFIRMED = 1;
    const STATUS_REJECTED = 0;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function getReviewId();

    public function setReviewId(int $reviewId);

    public function getDealerId();

    public function setDealerId(int $dealerId);

    public function getRate();

    public function setRate(string $rate);

    public function getTitle();

    public function setTitle(string $title);

    public function getMessage();

    public function setMessage(string $message);

    public function getIsConfirmed();

    public function setIsConfirmed(bool $isConfirmed);

    public function getCreatedAt();

    public function setCreatedAt(string $createdAt);

    public function getUpdatedAt();

    public function setUpdatedAt(string $updatedAt);
}
