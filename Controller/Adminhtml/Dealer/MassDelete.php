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

namespace RomanYurkhanov\Dealer\Controller\Adminhtml\Dealer;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use RomanYurkhanov\Dealer\Api\DealerRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Exception;

class MassDelete extends \Magento\Backend\App\Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'RomanYurkhanov_Dealer::dealer';
    protected $dealerRepository;
    protected SearchCriteriaBuilder $searchCriteriaBuilder;

    public function __construct(
        Context                   $context,
        DealerRepositoryInterface $dealerRepository,
        SearchCriteriaBuilder     $searchCriteriaBuilder
    )
    {
        $this->dealerRepository = $dealerRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        parent::__construct($context);
    }

    public function execute()
    {
        $dealerIds = $this->getRequest()->getParam('selected', []);
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('dealer_id', $dealerIds, 'in')
            ->create();
        $collection = $this->dealerRepository->getList($searchCriteria)->getItems();

        $dealerDeleted = 0;
        foreach ($collection as $dealer) {
            try {
                $this->dealerRepository->delete($dealer);
                $dealerDeleted++;
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception $e) {
                $this->_getSession()->addException(
                    $e,
                    __('Something went wrong while deleting %1.', $dealer->getName())
                );
            }
        }

        if ($dealerDeleted) {
            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $dealerDeleted));
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
