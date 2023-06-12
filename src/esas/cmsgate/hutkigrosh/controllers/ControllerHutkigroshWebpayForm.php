<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 22.03.2018
 * Time: 12:32
 */

namespace esas\cmsgate\hutkigrosh\controllers;

use esas\cmsgate\hutkigrosh\protocol\HutkigroshProtocol;
use esas\cmsgate\hutkigrosh\protocol\HutkigroshWebPayRq;
use esas\cmsgate\hutkigrosh\protocol\HutkigroshWebPayRs;
use esas\cmsgate\hutkigrosh\wrappers\ConfigWrapperHutkigrosh;
use esas\cmsgate\Registry;
use esas\cmsgate\hutkigrosh\RegistryHutkigrosh;
use esas\cmsgate\hutkigrosh\utils\RequestParamsHutkigrosh;
use esas\cmsgate\hutkigrosh\view\client\ClientViewFieldsHutkigrosh;
use esas\cmsgate\wrappers\OrderWrapper;
use Exception;
use Throwable;

class ControllerHutkigroshWebpayForm extends ControllerHutkigrosh
{
    /**
     * @param OrderWrapper $orderWrapper
     * @return HutkigroshWebPayRs
     */
    public function process($orderWrapper)
    {
        try {
            $loggerMainString = "Order[" . $orderWrapper->getOrderNumberOrId() . "]: ";
            $this->logger->info($loggerMainString . "Controller started");
            $hg = new HutkigroshProtocol(ConfigWrapperHutkigrosh::fromRegistry());
            $resp = $hg->apiLogIn();
            if ($resp->hasError()) {
                $hg->apiLogOut();
                throw new Exception($resp->getResponseMessage());
            }
            $webPayRq = new HutkigroshWebPayRq();
            $webPayRq->setBillId($orderWrapper->getExtId());
            $webPayRq->setReturnUrl($this->generateSuccessReturnUrl($orderWrapper));
            $webPayRq->setCancelReturnUrl($this->generateUnsuccessReturnUrl($orderWrapper));
            $webPayRq->setButtonLabel(Registry::getRegistry()->getTranslator()->translate(ClientViewFieldsHutkigrosh::WEBPAY_BUTTON_LABEL));
            $webPayRs = $hg->apiWebPay($webPayRq);
            $hg->apiLogOut();
            $this->logger->info($loggerMainString . "Controller ended");
            return $webPayRs;
        } catch (Throwable $e) {
            $this->logger->error($loggerMainString . "Controller exception! ", $e);
        } catch (Exception $e) { // для совместимости с php 5
            $this->logger->error($loggerMainString . "Controller exception! ", $e);
        }
    }

    /**
     * При необходимости, может быть переопределен в дочерних классах
     * @param OrderWrapper $orderWrapper
     * @return string
     */
    public function generateSuccessReturnUrl(OrderWrapper $orderWrapper)
    {
        return RegistryHutkigrosh::getRegistry()->getUrlWebpay($orderWrapper) . '&' . RequestParamsHutkigrosh::WEBPAY_STATUS . '=payed';
    }

    public function generateUnsuccessReturnUrl(OrderWrapper $orderWrapper)
    {
        return RegistryHutkigrosh::getRegistry()->getUrlWebpay($orderWrapper) . '&' . RequestParamsHutkigrosh::WEBPAY_STATUS . '=failed';
    }

    public static function isSuccessReturnUrl() {
        return $_REQUEST[RequestParamsHutkigrosh::WEBPAY_STATUS] == "payed";
    }

}