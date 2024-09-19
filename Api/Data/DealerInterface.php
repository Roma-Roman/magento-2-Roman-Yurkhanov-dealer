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

interface DealerInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    const DEALER_ID = 'dealer_id';
    const CODE = 'code';
    const NAME = 'name';
    const LINK = 'link';
    const LOGO = 'logo';
    const DESCRIPTION = 'description';
    const CONTACT_INFO = 'contact_info';
    const IS_ENABLED = 'is_enabled';
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    /**
     * @return mixed
     */
    public function getDealerId();

    /**
     * @param int $dealerId
     * @return mixed
     */
    public function setDealerId(int $dealerId);

    /**
     * @return mixed
     */
    public function getCode();

    /**
     * @param string $code
     * @return mixed
     */
    public function setCode(string $code);

    /**
     * @return mixed
     */
    public function getName();

    /**
     * @param string $name
     * @return mixed
     */
    public function setName(string $name);

    /**
     * @return mixed
     */
    public function getLink();

    /**
     * @param string $link
     * @return mixed
     */
    public function setLink(string $link);

    /**
     * @return mixed
     */
    public function getLogo();

    /**
     * @param string $logo
     * @return mixed
     */
    public function setLogo(string $logo);

    /**
     * @return mixed
     */
    public function getDescription();

    /**
     * @param string $description
     * @return mixed
     */
    public function setDescription(string $description);

    /**
     * @return mixed
     */
    public function getContactInfo();

    /**
     * @param string $contactInfo
     * @return mixed
     */
    public function setContactInfo(string $contactInfo);

    /**
     * @return mixed
     */
    public function getIsEnabled();

    /**
     * @param bool $isEnabled
     * @return mixed
     */
    public function setIsEnabled(bool $isEnabled);
}
