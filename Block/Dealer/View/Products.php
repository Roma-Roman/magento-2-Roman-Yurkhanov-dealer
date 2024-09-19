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

class Products extends View
{
    public function getDealerProducts()
    {
        $dealerId = $this->getDealer()->getDealerId();
        $productCollection = $this->productCollection->create()
            ->addAttributeToSelect(
                ['product_url', 'name', 'store_id', 'small_image', 'price', 'visibility']
            )->addFieldToFilter('dealer', ['in' => $dealerId]);

        return $productCollection->getItems();
    }
}
