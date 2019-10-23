<?php

namespace esas\cmsgate\controllers;

use esas\cmsgate\protocol\HutkigroshBillInfoRq;
use esas\cmsgate\protocol\HutkigroshBillInfoRs;
use esas\cmsgate\protocol\HutkigroshProtocol;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\RequestParams;
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
     * @throws Exception
     */
    public function process($billId = null)
    {
        try {
            if ($billId == null)
                $billId = $_REQUEST[RequestParams::PURCHASE_ID];
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
            $this->localOrderWrapper = $this->getOrderWrapperForBill($this->billInfoRs);
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
                $this->onStatusPayed();
            } elseif ($this->billInfoRs->isStatusCanceled()) {
                $this->logger->info($loggerMainString . "Remote order status is 'Canceled'");
                $this->onStatusCanceled();
            } elseif ($this->billInfoRs->isStatusPending()) {
                $this->logger->info($loggerMainString . "Remote order status is 'Pending'");
                $this->onStatusPending();
            }
            $this->logger->info($loggerMainString . "Controller ended");
        } catch (Throwable $e) {
            $this->logger->error($loggerMainString . "Controller exception! ", $e);
        } catch (Exception $e) { // для совместимости с php 5
            $this->logger->error($loggerMainString . "Controller exception! ", $e);
        }
    }

    /**
     * @param HutkigroshBillInfoRs $billInfoRs
     * @return OrderWrapper
     */
    public function getOrderWrapperForBill($billInfoRs)
    {
        return Registry::getRegistry()->getOrderWrapper($billInfoRs->getInvId()); // будет работать, только если orderId = orderNumber
    }

    /**
     * @param $status
     * @throws Throwable
     */
    public function updateStatus($status)
    {
        if (isset($status) && $this->localOrderWrapper->getStatus() != $status) {
            $this->logger->info("Setting status[" . $status . "] for order[" . $this->billInfoRs->getInvId() . "]...");
            $this->localOrderWrapper->updateStatus($status);
        }
    }

    /**
     * @throws Throwable
     */
    public function onStatusPayed()
    {
        $this->updateStatus($this->configWrapper->getBillStatusPayed());
    }

    /**
     * @throws Throwable
     */
    public function onStatusCanceled()
    {
        $this->updateStatus($this->configWrapper->getBillStatusCanceled());
    }

    /**
     * @throws Throwable
     */
    public function onStatusPending()
    {
        $this->updateStatus($this->configWrapper->getBillStatusPending());
    }
}