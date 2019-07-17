<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 22.03.2018
 * Time: 12:32
 */

namespace esas\cmsgate\controllers;

use esas\cmsgate\protocol\HutkigroshProtocol;
use esas\cmsgate\protocol\HutkigroshWebPayRq;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\RequestParams;
use esas\cmsgate\view\client\ViewFields;
use esas\cmsgate\wrappers\OrderWrapper;
use Exception;
use Throwable;

abstract class ControllerHutkigroshWebpayForm extends ControllerHutkigrosh
{
    /**
     * @param $billId
     * @return \esas\cmsgate\protocol\HutkigroshWebPayRs
     * @throws Throwable
     */
    public function process(OrderWrapper $orderWrapper)
    {
        try {
            $loggerMainString = "Order[" . $orderWrapper->getOrderNumber() . "]: ";
            $this->logger->info($loggerMainString . "Controller started");
            $hg = new HutkigroshProtocol($this->configWrapper);
            $resp = $hg->apiLogIn();
            if ($resp->hasError()) {
                $hg->apiLogOut();
                throw new Exception($resp->getResponseMessage());
            }
            $webPayRq = new HutkigroshWebPayRq();
            $webPayRq->setBillId($orderWrapper->getExtId());
            $webPayRq->setReturnUrl($this->generateSuccessReturnUrl($orderWrapper));
            $webPayRq->setCancelReturnUrl($this->generateUnsuccessReturnUrl($orderWrapper));
            $webPayRq->setButtonLabel(Registry::getRegistry()->getTranslator()->translate(ViewFields::WEBPAY_BUTTON_LABEL));
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
        return $this->getReturnUrl($orderWrapper) . '&' . RequestParams::WEBPAY_STATUS . '=payed';
    }

    public function generateUnsuccessReturnUrl(OrderWrapper $orderWrapper)
    {
        return $this->getReturnUrl($orderWrapper) . '&' . RequestParams::WEBPAY_STATUS . '=failed';
    }


    /**
     * Основная часть URL для возврата с формы webpay (чаще всего current_url)
     * @return string
     */
    public abstract function getReturnUrl(OrderWrapper $orderWrapper);
}