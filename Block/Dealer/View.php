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

namespace RomanYurkhanov\Dealer\Block\Dealer;

use RomanYurkhanov\Dealer\Api\DealerRepositoryInterface;
use RomanYurkhanov\Dealer\Api\DealerReviewsRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\UrlInterface;

class View extends Template
{

    protected DealerRepositoryInterface $dealerRepository;
    protected DealerReviewsRepositoryInterface $dealerReviewsRepository;
    protected ProductRepositoryInterface $productRepository;
    protected ProductCollectionFactory $productCollection;
    protected SearchCriteriaBuilder $searchCriteriaBuilder;
    protected UrlInterface $urlBuilder;

    public function __construct(
        Context                          $context,
        DealerRepositoryInterface        $dealerRepository,
        DealerReviewsRepositoryInterface $dealerReviewsRepository,
        ProductRepositoryInterface       $productRepository,
        ProductCollectionFactory         $productCollection,
        SearchCriteriaBuilder            $searchCriteriaBuilder,
        UrlInterface                     $urlBuilder,
        array                            $data = []

    )
    {
        $this->dealerRepository = $dealerRepository;
        $this->dealerReviewsRepository = $dealerReviewsRepository;
        $this->productRepository = $productRepository;
        $this->productCollection = $productCollection;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $data);
    }

    public function getDealer()
    {
        $dealerCode = $this->_request->getParam('code');
        return $this->dealerRepository->getByCode($dealerCode);
    }

    public function getLogoUrl()
    {
        $folderName = \RomanYurkhanov\Dealer\Model\ImageUploader::IMAGE_PATH;
        $storeLogoPath = $this->getDealer()->getLogo();

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
