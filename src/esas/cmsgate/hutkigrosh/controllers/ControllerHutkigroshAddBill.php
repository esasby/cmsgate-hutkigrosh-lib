<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 22.03.2018
 * Time: 14:13
 */

namespace esas\cmsgate\hutkigrosh\controllers;

use esas\cmsgate\hutkigrosh\RegistryHutkigrosh;
use esas\cmsgate\protocol\Amount;
use esas\cmsgate\hutkigrosh\protocol\HutkigroshBillNewRq;
use esas\cmsgate\hutkigrosh\protocol\HutkigroshBillNewRs;
use esas\cmsgate\hutkigrosh\protocol\BillProduct;
use esas\cmsgate\hutkigrosh\protocol\HutkigroshProtocol;
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
    public function process($orderWrapper)
    {
        try {
            if (is_numeric($orderWrapper)) //если передан orderId
                $orderWrapper = $this->registry->getOrderWrapper($orderWrapper);
            if (empty($orderWrapper) || empty($orderWrapper->getOrderNumberOrId())) {
                throw new Exception("Incorrect method call! orderWrapper is null or not well initialized");
            }
            if (!empty($orderWrapper->getExtId())) {
                throw new Exception("Order is already processed");
            }
            $loggerMainString = "Order[" . $orderWrapper->getOrderNumberOrId() . "]: ";
            $this->logger->info($loggerMainString . "Controller started");
            $hg = new HutkigroshProtocol($this->configWrapper);
            $resp = $hg->apiLogIn();
            if ($resp->hasError()) {
                $hg->apiLogOut();
                throw new Exception($resp->getResponseMessage(), $resp->getResponseCode());
            }
            $billNewRq = new HutkigroshBillNewRq();
            $billNewRq->setEripId($this->configWrapper->getEripId());
            $billNewRq->setInvId($orderWrapper->getOrderNumberOrId());
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
            if ($resp->hasError() || empty($resp->getBillId())) {
                $this->logger->error($loggerMainString . "Bill was not added...");
                RegistryHutkigrosh::getRegistry()->getHooks()->onAddBillFailed($orderWrapper, $resp);
                throw new Exception($resp->getResponseMessage(), $resp->getResponseCode());
            } else {
                $this->logger->info($loggerMainString . "Bill[" . $resp->getBillId() . "] was successfully added");
                RegistryHutkigrosh::getRegistry()->getHooks()->onAddBillSuccess($orderWrapper, $resp);
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
}