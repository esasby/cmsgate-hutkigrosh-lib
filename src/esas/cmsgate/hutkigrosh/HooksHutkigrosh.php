<?php


namespace esas\cmsgate\hutkigrosh;


use esas\cmsgate\Hooks;
use esas\cmsgate\hutkigrosh\protocol\HutkigroshBillInfoRs;
use esas\cmsgate\hutkigrosh\protocol\HutkigroshBillNewRs;
use esas\cmsgate\OrderStatus;
use esas\cmsgate\wrappers\OrderWrapper;

class HooksHutkigrosh extends Hooks
{
    public function onAddBillSuccess(OrderWrapper $orderWrapper, HutkigroshBillNewRs $resp) {
        $orderWrapper->saveExtId($resp->getBillId());
        $orderWrapper->updateStatusWithLogging(OrderStatus::pending());
    }

    public function onAddBillFailed(OrderWrapper $orderWrapper, HutkigroshBillNewRs $resp) {
        $orderWrapper->updateStatusWithLogging(OrderStatus::failed());
    }

    public function onNotifyStatusPayed(OrderWrapper $orderWrapper, HutkigroshBillInfoRs $resp) {
        $orderWrapper->updateStatusWithLogging(OrderStatus::payed());
    }

    public function onNotifyStatusCanceled(OrderWrapper $orderWrapper, HutkigroshBillInfoRs $resp) {
        $orderWrapper->updateStatusWithLogging(OrderStatus::canceled());
    }

    public function onNotifyStatusPending(OrderWrapper $orderWrapper, HutkigroshBillInfoRs $resp) {
        $orderWrapper->updateStatusWithLogging(OrderStatus::pending());
    }
}