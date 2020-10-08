<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 22.03.2018
 * Time: 14:13
 */

namespace esas\cmsgate\hutkigrosh\controllers;

use esas\cmsgate\hutkigrosh\utils\RequestParamsHutkigrosh;
use esas\cmsgate\hutkigrosh\view\client\CompletionPanelHutkigrosh;
use Exception;
use Throwable;

class ControllerHutkigroshCompletionPageWebpay extends ControllerHutkigrosh
{
    /**
     * @param $orderId
     * @return CompletionPanelHutkigrosh
     * @throws Throwable
     */
    public function process($orderId)
    {
        try {
            $orderWrapper = $this->registry->getOrderWrapper($orderId);
            $loggerMainString = "Order[" . $orderWrapper->getOrderNumber() . "]: ";
            $this->logger->info($loggerMainString . "Controller started");
            $completionPanel = $this->registry->getCompletionPanel($orderWrapper);
            $completionPanel->setAlfaclickUrl(false);
            $controller = new ControllerHutkigroshWebpayForm();
            $webpayResp = $controller->process($orderWrapper);
            $completionPanel->setWebpayForm($webpayResp->getHtmlForm());
            if (array_key_exists(RequestParamsHutkigrosh::WEBPAY_STATUS, $_REQUEST))
                $completionPanel->setWebpayStatus($_REQUEST[RequestParamsHutkigrosh::WEBPAY_STATUS]);
            return $completionPanel;
        } catch (Throwable $e) {
            $this->logger->error($loggerMainString . "Controller exception! ", $e);
            throw $e;
        } catch (Exception $e) { // для совместимости с php 5
            $this->logger->error($loggerMainString . "Controller exception! ", $e);
            throw $e;
        }
    }
}