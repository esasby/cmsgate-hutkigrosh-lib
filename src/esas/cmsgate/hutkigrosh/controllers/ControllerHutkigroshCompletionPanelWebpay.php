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
use esas\cmsgate\Registry;
use esas\cmsgate\wrappers\OrderWrapper;
use Exception;
use Throwable;

class ControllerHutkigroshCompletionPanelWebpay extends ControllerHutkigrosh
{
    /**
     * @param int|OrderWrapper $orderWrapper
     * @return CompletionPanelHutkigrosh
     * @throws Throwable
     */
    public function process($orderWrapper)
    {
        try {
            if (is_numeric($orderWrapper)) //если передан orderId
                $orderWrapper = $this->registry->getOrderWrapper($orderWrapper);
            $loggerMainString = "Order[" . $orderWrapper->getOrderNumberOrId() . "]: ";
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
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
            throw $e;
        } catch (Exception $e) { // для совместимости с php 5
            $this->logger->error($loggerMainString . "Controller exception! ", $e);
            Registry::getRegistry()->getMessenger()->addErrorMessage($e->getMessage());
            throw $e;
        }
    }
}