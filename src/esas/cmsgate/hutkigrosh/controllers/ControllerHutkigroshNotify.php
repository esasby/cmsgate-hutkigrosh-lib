<?php

namespace esas\cmsgate\hutkigrosh\controllers;

use esas\cmsgate\hutkigrosh\protocol\HutkigroshBillInfoRq;
use esas\cmsgate\hutkigrosh\protocol\HutkigroshBillInfoRs;
use esas\cmsgate\hutkigrosh\protocol\HutkigroshProtocol;
use esas\cmsgate\hutkigrosh\RegistryHutkigrosh;
use esas\cmsgate\hutkigrosh\utils\RequestParamsHutkigrosh;
use esas\cmsgate\utils\StringUtils;
use esas\cmsgate\wrappers\OrderWrapper;
use Exception;
use Throwable;

/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 22.03.2018
 * Time: 11:37
 */
class ControllerHutkigroshNotify extends ControllerHutkigrosh
{
    /**
     * @var OrderWrapper
     */
    protected $localOrderWrapper;

    /**
     * @var HutkigroshBillInfoRs
     */
    protected $billInfoRs;

    /**
     * @param $billId
     * @return HutkigroshBillInfoRs
     * @throws Exception
     */
    public function process($billId = null)
    {
        try {
            if ($billId == null)
                $billId = $_REQUEST[RequestParamsHutkigrosh::PURCHASE_ID];
            $loggerMainString = "Bill[" . $billId . "]: ";
            $this->logger->info($loggerMainString . "Controller started");
            if (empty($billId))
                throw new Exception('Wrong billid[' . $billId . "]");
            $this->logger->info($loggerMainString . "Loading order data from Hutkigrosh gateway...");
            $hg = new HutkigroshProtocol($this->configWrapper);
            $resp = $hg->apiLogIn();
            if ($resp->hasError()) {
                $hg->apiLogOut();
                throw new Exception($resp->getResponseMessage(), $resp->getResponseCode());
            }
            $this->billInfoRs = $hg->apiBillInfo(new HutkigroshBillInfoRq($billId));
            $hg->apiLogOut();
            if ($this->billInfoRs->hasError())
                throw new Exception($resp->getResponseMessage(), $resp->getResponseCode());
            $this->logger->info($loggerMainString . 'Loading local order object for id[' . $this->billInfoRs->getInvId() . "]");
            $this->localOrderWrapper = RegistryHutkigrosh::getRegistry()->getOrderWrapperByOrderNumberOrId($this->billInfoRs->getOrderId());
            if (empty($this->localOrderWrapper))
                $this->localOrderWrapper = RegistryHutkigrosh::getRegistry()->getOrderWrapperByExtId($billId);
            if (empty($this->localOrderWrapper))
                throw new Exception('Can not load order info for id[' . $this->billInfoRs->getInvId() . "]");
            if (!$this->configWrapper->isSandbox() // на тестовой системе это пока не работает
                && (!StringUtils::compare($this->billInfoRs->getFullName(), $this->localOrderWrapper->getFullName())
                    || !$this->billInfoRs->getAmount()->isEqual($this->localOrderWrapper->getAmountObj()))) {
                throw new Exception("Unmapped purchaseid: localFullname[" . $this->localOrderWrapper->getFullName()
                    . "], remoteFullname[" . $this->billInfoRs->getFullName()
                    . "], localAmount[" . $this->localOrderWrapper->getAmountObj()
                    . "], remoteAmount[" . $this->billInfoRs->getAmount() . "]");
            }
            if ($this->billInfoRs->isStatusPayed()) {
                $this->logger->info($loggerMainString . "Remote order status is 'Payed'");
                RegistryHutkigrosh::getRegistry()->getHooks()->onNotifyStatusPayed($this->localOrderWrapper, $this->billInfoRs);
            } elseif ($this->billInfoRs->isStatusCanceled()) {
                $this->logger->info($loggerMainString . "Remote order status is 'Canceled'");
                RegistryHutkigrosh::getRegistry()->getHooks()->onNotifyStatusCanceled($this->localOrderWrapper, $this->billInfoRs);
            } elseif ($this->billInfoRs->isStatusPending()) {
                $this->logger->info($loggerMainString . "Remote order status is 'Pending'");
                RegistryHutkigrosh::getRegistry()->getHooks()->onNotifyStatusPending($this->localOrderWrapper, $this->billInfoRs);
            }
            $this->logger->info($loggerMainString . "Controller ended");
        } catch (Throwable $e) {
            $this->logger->error($loggerMainString . "Controller exception! ", $e);
        } catch (Exception $e) { // для совместимости с php 5
            $this->logger->error($loggerMainString . "Controller exception! ", $e);
        } finally {
            return $this->billInfoRs;
        }
    }
}