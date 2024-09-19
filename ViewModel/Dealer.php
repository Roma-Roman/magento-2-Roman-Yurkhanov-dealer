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

namespace RomanYurkhanov\Dealer\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use RomanYurkhanov\Dealer\Block\Dealer\View;

class Dealer extends View implements ArgumentInterface
{
    public function getDealerData($dealerId)
    {
        try {
            return $this->dealerRepository->getById($dealerId);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            return null;
        }
    }

    public function getDealerLogoUrl($dealerId)
    {
        $folderName = \RomanYurkhanov\Dealer\Model\ImageUploader::IMAGE_PATH;
        $storeLogoPath = $this->getDealerData($dealerId)->getLogo();

        if (empty($storeLogoPath)) {
            $logoUrl = '';
        } else {
            $path = $folderName . '/' . $storeLogoPath;
            $logoUrl = $this->urlBuilder
                    ->getBaseUrl(['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]) . $path;
        }

        return $logoUrl;
    }
}
