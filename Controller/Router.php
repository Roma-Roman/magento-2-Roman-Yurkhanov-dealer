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

namespace RomanYurkhanov\Dealer\Controller;

use Magento\Framework\App\Action\Forward;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;
use RomanYurkhanov\Dealer\Api\DealerRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

class Router implements RouterInterface
{
    protected DealerRepositoryInterface $dealerRepository;

    protected SearchCriteriaBuilder $searchCriteriaBuilder;

    private $actionFactory;

    public function __construct(
        ActionFactory             $actionFactory,
        DealerRepositoryInterface $dealerRepository,
        SearchCriteriaBuilder     $searchCriteriaBuilder
    )
    {
        $this->actionFactory = $actionFactory;
        $this->dealerRepository = $dealerRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function match(RequestInterface $request): ?ActionInterface
    {
        $identifier = trim($request->getPathInfo(), '/');

        if (strpos($identifier, 'dealer') !== false) {
            $pathInfo = $request->getPathInfo();
            $params = explode('/', $pathInfo);
            $paramDealer = $params[2] ?? '';

            if ($paramDealer == 'list') {
                $request->setModuleName('dealer');
                $request->setControllerName('all');
                $request->setActionName('index');
                $request->setPathInfo('/dealer/list/index');
                return $this->actionFactory->create(Forward::class, ['request' => $request]);
            }

            if ($paramDealer == 'account') {
                $request->setModuleName('dealer');
                $request->setControllerName('account');
                $request->setActionName('index');
                $request->setPathInfo('/dealer/account/index');
                return $this->actionFactory->create(Forward::class, ['request' => $request]);
            }

            $searchCriteria = $paramDealer;
            $dealerCode = $this->dealerRepository->getByCode($searchCriteria);

            if ($dealerCode) {
                $request->setModuleName('dealer');
                $request->setControllerName('view');
                $request->setActionName('index');
                $request->setParams(['code' => $dealerCode->getCode()]);
                return $this->actionFactory->create(Forward::class, ['request' => $request]);
            }
        }

        return null;
    }
}
