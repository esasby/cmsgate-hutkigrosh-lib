<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 22.03.2018
 * Time: 14:13
 */

namespace esas\cmsgate\controllers;

use esas\cmsgate\utils\RequestParams;
use esas\cmsgate\view\client\CompletionPanel;
use esas\cmsgate\wrappers\OrderWrapper;
use Exception;
use Throwable;

class ControllerHutkigroshCompletionPage extends ControllerHutkigrosh
{
    private $alfaclickUrl;
    private $webpayReturnUrl;

    /**
     * ControllerHutkigroshWebpayForm constructor.
     * @param $returnUrl
     */
    public function __construct($alfaclickUrl, $webpayReturnUrl)
    {
        parent::__construct();
        $this->alfaclickUrl = $alfaclickUrl;
        $this->webpayReturnUrl = $webpayReturnUrl;
    }

    /**
     * @param OrderWrapper $orderWrapper
     * @return CompletionPanel
     * @throws Throwable
     */
    public function process($orderNumber)
    {
        try {
            $orderWrapper = $this->registry->getOrderWrapper($orderNumber);
            $loggerMainString = "Order[" . $orderWrapper->getOrderNumber() . "]: ";
            $this->logger->info($loggerMainString . "Controller started");
            $completionPanel = $this->registry->getCompletionPanel($orderWrapper);
            if ($this->configWrapper->isAlfaclickSectionEnabled()) {
                $completionPanel->setAlfaclickUrl($this->alfaclickUrl);
            }
            if ($this->configWrapper->isWebpaySectionEnabled()) {
                $controller = new ControllerHutkigroshWebpayFormSimple($this->webpayReturnUrl);
                $webpayResp = $controller->process($orderWrapper);
                $completionPanel->setWebpayForm($webpayResp->getHtmlForm());
                if (array_key_exists(RequestParams::WEBPAY_STATUS, $_REQUEST))
                    $completionPanel->setWebpayStatus($_REQUEST[RequestParams::WEBPAY_STATUS]);
            }
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