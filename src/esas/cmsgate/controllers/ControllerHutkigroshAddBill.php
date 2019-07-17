<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 22.03.2018
 * Time: 14:13
 */

namespace esas\cmsgate\controllers;

use esas\cmsgate\protocol\Amount;
use esas\cmsgate\protocol\HutkigroshBillNewRq;
use esas\cmsgate\protocol\HutkigroshBillNewRs;
use esas\cmsgate\protocol\BillProduct;
use esas\cmsgate\protocol\HutkigroshProtocol;
use esas\cmsgate\wrappers\OrderWrapper;
use Exception;
use Throwable;

class ControllerHutkigroshAddBill extends ControllerHutkigrosh
{
    /**
     * @param OrderWrapper $orderWrapper
     * @return HutkigroshBillNewRs
     * @throws Throwable
     */
    public function process(OrderWrapper $orderWrapper)
    {
        try {
            if (empty($orderWrapper)) {
                throw new Exception("Incorrect method call! orderWrapper is null");
            }
            if (!empty($orderWrapper->getExtId())) {
                throw new Exception("Order is already processed");
            }
            $loggerMainString = "Order[" . $orderWrapper->getOrderNumber() . "]: ";
            $this->logger->info($loggerMainString . "Controller started");
            $hg = new HutkigroshProtocol($this->configWrapper);
            $resp = $hg->apiLogIn();
            if ($resp->hasError()) {
                $hg->apiLogOut();
                throw new Exception($resp->getResponseMessage(), $resp->getResponseCode());
            }
            $billNewRq = new HutkigroshBillNewRq();
            $billNewRq->setEripId($this->configWrapper->getEripId());
            $billNewRq->setInvId($orderWrapper->getOrderNumber());
            $billNewRq->setFullName($orderWrapper->getFullName());
            $billNewRq->setMobilePhone($orderWrapper->getMobilePhone());
            $billNewRq->setEmail($orderWrapper->getEmail());
            $billNewRq->setFullAddress($orderWrapper->getAddress());
            $billNewRq->setAmount(new Amount($orderWrapper->getAmount(), $orderWrapper->getCurrency()));
            $billNewRq->setNotifyByEMail($this->configWrapper->isEmailNotification());
            $billNewRq->setNotifyByMobilePhone($this->configWrapper->isSmsNotification());
            $billNewRq->setDueInterval($this->configWrapper->getDueInterval());
            foreach ($orderWrapper->getProducts() as $cartProduct) {
                $product = new BillProduct();
                $product->setName($cartProduct->getName());
                $product->setInvId($cartProduct->getInvId());
                $product->setCount($cartProduct->getCount());
                $product->setUnitPrice($cartProduct->getUnitPrice());
                $billNewRq->addProduct($product);
                unset($product); //??
            }
            $resp = $hg->apiBillNew($billNewRq);
            $hg->apiLogOut();
            if ($resp->hasError()) {
                $this->logger->error($loggerMainString . "Bill was not added. Setting status[" . $this->configurationWrapper->getBillStatusFailed() . "]...");
                $this->onFailed($orderWrapper, $resp);
                throw new Exception($resp->getResponseMessage(), $resp->getResponseCode());  
            } else {
                $this->logger->info($loggerMainString . "Bill[" . $resp->getBillId() . "] was successfully added. Updating status[" . $this->configurationWrapper->getBillStatusPending() . "]...");
                $this->onSuccess($orderWrapper, $resp);
            }
            return $resp;
        } catch (Throwable $e) {
            $this->logger->error($loggerMainString . "Controller exception! ", $e);
            throw $e;
        } catch (Exception $e) { // для совместимости с php 5
            $this->logger->error($loggerMainString . "Controller exception! ", $e);
            throw $e;
        }
    }

    /**
     * Изменяет статус заказа при успешном высталении счета
     * Вынесено в отдельный метод, для возможности owerrid-а
     * (например, кроме статуса заказа надо еще обновить статус транзакции)
     * @param OrderWrapper $orderWrapper
     * @param HutkigroshBillNewRs $resp
     * @throws Throwable
     */
    public function onSuccess(OrderWrapper $orderWrapper, HutkigroshBillNewRs $resp)
    {
        $orderWrapper->saveExtId($resp->getBillId());
        $orderWrapper->updateStatus($this->configWrapper->getBillStatusPending());
    }

    /**
     * @param OrderWrapper $orderWrapper
     * @param HutkigroshBillNewRs $resp
     * @throws Throwable
     */
    public function onFailed(OrderWrapper $orderWrapper, HutkigroshBillNewRs $resp)
    {
        $orderWrapper->updateStatus($this->configWrapper->getBillStatusFailed());
    }
}