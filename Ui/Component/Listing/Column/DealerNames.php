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

namespace RomanYurkhanov\Dealer\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

class DealerNames extends Column
{
    /**
     * Apply sorting
     *
     * @return void
     */
    protected function applySorting()
    {
        $sorting = $this->getContext()->getRequestParam('sorting');
        $isSortable = $this->getData('config/sortable');
        if (
            $isSortable !== false
            && !empty($sorting['field'])
            && !empty($sorting['direction'])
            && $sorting['field'] === $this->getName()
            && in_array(strtoupper($sorting['direction']), ['ASC', 'DESC'], true)
        ) {
            $collection = $this->getContext()->getDataProvider()->getCollection();

            // Join the romanyurkhanov_dealer table to get dealer_name
            $collection->getSelect()->join(
                ['dealer_table' => 'romanyurkhanov_dealer'], // Alias for romanyurkhanov_dealer table
                'main_table.dealer_id = dealer_table.dealer_id', // Join condition on dealer_id
                ['dealer_name' => 'dealer_table.name'] // Select dealer_name from romanyurkhanov_dealer table
            );

            // Apply sorting on the dealer_name field
            $collection->getSelect()->order('dealer_name ' . $sorting['direction']);
        }
    }

}
