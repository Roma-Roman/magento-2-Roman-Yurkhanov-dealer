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

namespace RomanYurkhanov\Dealer\Cron;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Psr\Log\LoggerInterface;
use RomanYurkhanov\Dealer\Api\DealerRepositoryInterface;
use RomanYurkhanov\Dealer\Api\DealerReviewsRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

class DealerDescriptionUpdate
{
    private CollectionFactory $productCollectionFactory;
    private DealerRepositoryInterface $dealerRepository;
    private DealerReviewsRepositoryInterface $dealerReviewsRepository;
    private SearchCriteriaBuilder $searchCriteriaBuilder;
    private AttributeRepositoryInterface $attributeRepository;
    private LoggerInterface $logger;

    public function __construct(
        CollectionFactory                $productCollectionFactory,
        DealerRepositoryInterface        $dealerRepository,
        DealerReviewsRepositoryInterface $dealerReviewsRepository,
        SearchCriteriaBuilder            $searchCriteriaBuilder,
        AttributeRepositoryInterface     $attributeRepository,
        LoggerInterface                  $logger
    )
    {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->dealerRepository = $dealerRepository;
        $this->dealerReviewsRepository = $dealerReviewsRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->attributeRepository = $attributeRepository;
        $this->logger = $logger;
    }

    public function execute()
    {
        try {
            // Retrieve the dealer description attribute
            $attribute = $this->attributeRepository->get('catalog_product', 'dealer_description');

            // Retrieve all products
            $products = $this->productCollectionFactory->create()
                ->addAttributeToSelect([
                    'dealer_description',
                    'dealer'
                ]);

            foreach ($products as $product) {
                // Get the dealer
                $productId = $product->getId();
                $dealerId = $product->getData('dealer');
                if(!$dealerId) {
                    continue;
                }
                $dealer = $this->dealerRepository->getById($dealerId);

                // Calculate the dealer rating based on reviews
                $dealerRating = $this->calculateDealerRating($dealerId);

                // Build the dealer description value
                $dealerDescription = $dealer->getName() . ' (' . $dealer->getCode() . '). Rating: ' . $dealerRating;

                // Update and save the product attribute
                $product->setData($attribute->getAttributeCode(), $dealerDescription);
                $product->getResource()->saveAttribute($product, $attribute->getAttributeCode());

            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * Calculate the dealer rating based on reviews
     *
     * @param int $dealerId
     * @return float
     */
    private function calculateDealerRating($dealerId)
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('dealer_id', $dealerId, 'in')
            ->addFilter('is_confirmed', true)
            ->create();

        $dealerReviews = $this->dealerReviewsRepository->getList($searchCriteria)->getItems();

        $ratingSum = 0;
        $reviewCount = count($dealerReviews);

        foreach ($dealerReviews as $review) {
            $ratingSum += $review->getRate();
        }

        if ($reviewCount > 0) {
            $averageRating = $ratingSum / $reviewCount;
        } else {
            $averageRating = 0;
        }

        return round($averageRating, 1);
    }
}
