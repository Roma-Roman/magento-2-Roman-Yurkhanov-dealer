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

namespace RomanYurkhanov\Dealer\Block\Dealer\View\Reviews;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use RomanYurkhanov\Dealer\Api\DealerRepositoryInterface;
use RomanYurkhanov\Dealer\Api\DealerReviewsRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use RomanYurkhanov\Dealer\Block\Dealer\View;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\UrlInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Message\ManagerInterface;

class Form extends View
{
    protected $messageManager;
    protected $jsLayout;

    private $serializer;

    public function __construct(
        Context                          $context,
        DealerRepositoryInterface        $dealerRepository,
        DealerReviewsRepositoryInterface $dealerReviewsRepository,
        ProductRepositoryInterface       $productRepository,
        ProductCollectionFactory         $productCollection,
        SearchCriteriaBuilder            $searchCriteriaBuilder,
        UrlInterface                     $urlBuilder,
        ManagerInterface                 $messageManager,
        array                            $data = [],
        Json                             $serializer = null
    )
    {
        $this->dealerRepository = $dealerRepository;
        $this->dealerReviewsRepository = $dealerReviewsRepository;
        $this->productRepository = $productRepository;
        $this->productCollection = $productCollection;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->urlBuilder = $urlBuilder;
        parent::__construct(
            $context,
            $dealerRepository,
            $dealerReviewsRepository,
            $productRepository,
            $productCollection,
            $searchCriteriaBuilder,
            $urlBuilder,
            $data
        );
        $this->messageManager = $messageManager;
        $this->jsLayout = isset($data['jsLayout']) ? $data['jsLayout'] : [];
        $this->serializer = $serializer ?: ObjectManager::getInstance()
            ->get(Json::class);
    }

    public function getJsLayout()
    {
        return $this->serializer->serialize($this->jsLayout);
    }

    public function getAction()
    {
        $dealerCode = $this->getRequest()->getParam('code');
        $dealer = $this->dealerRepository->getByCode($dealerCode);

        return $this->getUrl(
            'dealer/view_reviews/post',
            [
                '_secure' => $this->getRequest()->isSecure(),
                'dealer_id' => $dealer->getDealerId(),
            ]
        );
    }

    public function getRatings()
    {
        return $options = [
            ['value' => 1, 'label' => ('Rating_1')],
            ['value' => 2, 'label' => ('Rating_2')],
            ['value' => 3, 'label' => ('Rating_3')],
            ['value' => 4, 'label' => ('Rating_4')],
            ['value' => 5, 'label' => __('Rating_5')],
        ];
    }
}
