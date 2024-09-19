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

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action\Context;
use RomanYurkhanov\Dealer\Api\DealerRepositoryInterface;
use RomanYurkhanov\Dealer\Model\DealerFactory;
use RomanYurkhanov\Dealer\Model\ImageUploader;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends Action implements HttpPostActionInterface
{
    protected DataPersistorInterface $dataPersistor;

    private DealerRepositoryInterface $dealerRepository;

    private DealerFactory $dealerFactory;

    protected ImageUploader $imageUploader;

    public function __construct(
        Context                   $context,
        DataPersistorInterface    $dataPersistor,
        DealerRepositoryInterface $dealerRepository,
        DealerFactory             $dealerFactory,
        ImageUploader             $imageUploader
    )
    {
        $this->dataPersistor = $dataPersistor;
        $this->dealerRepository = $dealerRepository;
        $this->dealerFactory = $dealerFactory;
        $this->imageUploader = $imageUploader;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $model = $this->dealerFactory->create();

            $id = $this->getRequest()->getParam('dealer_id');
            if ($id) {
                try {
                    $model = $this->dealerRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This dealer no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }

            if (isset($data['logo'][0]['name']) && isset($data['logo'][0]['tmp_name'])) {
                $data['logo'] = $data['logo'][0]['name'];
                $this->imageUploader->moveFileFromTmp($data['logo']);
            } elseif (isset($data['logo'][0]['name']) && !isset($data['logo'][0]['tmp_name'])) {
                $data['logo'] = $data['logo'][0]['name'];
            } else {
                $data['logo'] = '';
            }

            $model->setData($data);

            try {
                $this->dealerRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the dealer.'));
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['dealer_id' => $id]);
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the dealer.'));
            }
        }
        return $resultRedirect->setPath('*/*/');
    }
}
