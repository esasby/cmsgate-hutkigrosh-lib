<?php

namespace esas\cmsgate\hutkigrosh\controllers;

use esas\cmsgate\hutkigrosh\protocol\HutkigroshAlfaclickRq;
use esas\cmsgate\hutkigrosh\protocol\HutkigroshProtocol;
use esas\cmsgate\hutkigrosh\utils\RequestParamsHutkigrosh;
use Exception;
use Throwable;

/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 22.03.2018
 * Time: 11:30
 */
class ControllerHutkigroshAlfaclick extends ControllerHutkigrosh
{
    public function process($billId = null, $phone = null)
    {
        try {
            if ($billId == null)
                $billId = $_REQUEST[RequestParamsHutkigrosh::BILL_ID];
            if ($phone == null)
                $phone = $_REQUEST[RequestParamsHutkigrosh::PHONE];
            $loggerMainString = "Bill[" . $billId . "]: ";
            $this->logger->info($loggerMainString . "Controller started");
            if (empty($billId) || empty($phone))
                throw new Exception('Wrong billid[' . $billId . "] or phone[" . $phone . "]");
            $hg = new HutkigroshProtocol(ConfigWrapperHutkigrosh::fromRegistry());
            $resp = $hg->apiLogIn();
            if ($resp->hasError()) {
                $hg->apiLogOut();
                throw new Exception($resp->getResponseMessage(), $resp->getResponseCode());
            }
            $alfaclickRq = new HutkigroshAlfaclickRq();
            $alfaclickRq->setBillId($billId);
            $alfaclickRq->setPhone($phone);

            $resp = $hg->apiAlfaClick($alfaclickRq);
            $hg->apiLogOut();
            $this->outputResult($resp->hasError());
            $this->logger->info($loggerMainString . "Controller ended");
        } catch (Throwable $e) {
            $this->logger->error($loggerMainString . "Controller exception! ", $e);
            $this->outputResult(true);
        } catch (Exception $e) { // для совместимости с php 5
            $this->logger->error($loggerMainString . "Controller exception! ", $e);
            $this->outputResult(true);
        }
    }

    /**
     * При необходимости формирования ответа в другом формате метод может быть переопреден в дочериних классах
     * @param $hasError
     */
    public function outputResult($hasError)
    {
        echo $hasError ? "error" : "ok";
    }

}