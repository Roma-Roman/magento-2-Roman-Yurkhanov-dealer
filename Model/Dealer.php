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
use RomanYurkhanov\Dealer\Api\Data\DealerInterface;
use RomanYurkhanov\Dealer\Model\ResourceModel\Dealer as DealerResource;

class Dealer extends AbstractModel implements DealerInterface
{
    protected function _construct()
    {
        $this->_init(DealerResource::class);
    }

    public function getDealerId()
    {
        return $this->getData(self::DEALER_ID);
    }

    public function setDealerId($dealerId)
    {
        return $this->setData(self::DEALER_ID, $dealerId);
    }

    public function getCode()
    {
        return $this->getData(self::CODE);
    }

    public function setCode($code)
    {
        return $this->setData(self::CODE, $code);
    }

    public function getName()
    {
        return $this->getData(self::NAME);
    }

    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    public function getLink()
    {
        return $this->getData(self::LINK);
    }

    public function setLink($link)
    {
        return $this->setData(self::LINK, $link);
    }

    public function getLogo()
    {
        return $this->getData(self::LOGO);
    }

    public function setLogo($logo)
    {
        return $this->setData(self::LOGO, $logo);
    }

    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    public function getContactInfo()
    {
        return $this->getData(self::CONTACT_INFO);
    }

    public function setContactInfo($contactInfo)
    {
        return $this->setData(self::CONTACT_INFO, $contactInfo);
    }

    public function getIsEnabled()
    {
        return $this->getData(self::IS_ENABLED);
    }

    public function setIsEnabled($isEnabled)
    {
        return $this->setData(self::IS_ENABLED, $isEnabled);
    }
}
