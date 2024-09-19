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

use Magento\Framework\Model\AbstractModel;
use RomanYurkhanov\Dealer\Api\Data\DealerReviewsInterface;
use RomanYurkhanov\Dealer\Model\ResourceModel\DealerReviews as DealerReviewsResource;

class DealerReviews extends AbstractModel implements DealerReviewsInterface
{
    protected function _construct()
    {
        $this->_init(DealerReviewsResource::class);
    }

    public function getReviewId()
    {
        return $this->getData(self::REVIEW_ID);
    }

    public function setReviewId($reviewId)
    {
        return $this->setData(self::REVIEW_ID, $reviewId);
    }

    public function getDealerId()
    {
        return $this->getData(self::DEALER_ID);
    }

    public function setDealerId($dealerId)
    {
        return $this->setData(self::DEALER_ID, $dealerId);
    }

    public function getRate()
    {
        return $this->getData(self::RATE);
    }

    public function setRate($rate)
    {
        return $this->setData(self::RATE, $rate);
    }

    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    public function getMessage()
    {
        return $this->getData(self::MESSAGE);
    }

    public function setMessage($message)
    {
        return $this->setData(self::MESSAGE, $message);
    }

    public function getIsConfirmed()
    {
        return $this->getData(self::IS_CONFIRMED);
    }

    public function setIsConfirmed($isConfirmed)
    {
        return $this->setData(self::IS_CONFIRMED, $isConfirmed);
    }

    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}
