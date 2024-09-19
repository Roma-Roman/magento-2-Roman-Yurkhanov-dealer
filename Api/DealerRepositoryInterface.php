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

use Magento\Framework\Api\SearchCriteriaInterface;
use RomanYurkhanov\Dealer\Api\Data\DealerInterface;

interface DealerRepositoryInterface
{
    /**
     * @param \RomanYurkhanov\Dealer\Api\Data\DealerInterface
     * @return \RomanYurkhanov\Dealer\Api\Data\DealerInterface
     */
    public function save(Data\DealerInterface $dealer);

    /**
     * @param int $dealerId
     * @return \RomanYurkhanov\Dealer\Api\Data\DealerInterface
     */
    public function getById($dealerId);

    /**
     * @param string $code
     * @return \RomanYurkhanov\Dealer\Api\Data\DealerInterface
     */
    public function getByCode($code);


    /**
     * @param \RomanYurkhanov\Dealer\Api\Data\DealerInterface
     * @return bool
     */
    public function delete(Data\DealerInterface $dealer);

    /**
     * @param int $dealerId
     * @return bool
     */
    public function deleteById($dealerId);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \RomanYurkhanov\Dealer\Api\Data\DealerSearchResultInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
