<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 22.03.2018
 * Time: 12:38
 */

namespace esas\cmsgate\controllers;


use CMain;
use COption;
use esas\cmsgate\lang\TranslatorBitrix;
use esas\cmsgate\lang\TranslatorOpencart;
use esas\cmsgate\utils\RequestParams;
use esas\cmsgate\wrappers\ConfigurationWrapperBitrix;
use esas\cmsgate\wrappers\ConfigurationWrapperOpencart;
use esas\cmsgate\wrappers\OrderWrapper;
use Registry;

class ControllerHutkigroshWebpayFormSimple extends ControllerHutkigroshWebpayForm
{
    private $returnUrl;

    /**
     * ControllerHutkigroshWebpayForm constructor.
     * @param $returnUrl
     */
    public function __construct($returnUrl)
    {
        parent::__construct();
        $this->returnUrl = $returnUrl;
    }

    /**
     * Основная часть URL для возврата с формы webpay (чаще всего current_url)
     * @return string
     */
    public function getReturnUrl(OrderWrapper $orderWrapper)
    {
        return $this->returnUrl
            . "&" . RequestParams::ORDER_NUMBER . "=" . $orderWrapper->getOrderId()
            . "&" . RequestParams::BILL_ID . "=" . $orderWrapper->getExtId();
    }
}