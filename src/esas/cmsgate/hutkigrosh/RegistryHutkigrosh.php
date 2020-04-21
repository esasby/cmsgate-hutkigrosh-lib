<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 01.10.2018
 * Time: 11:35
 */

namespace esas\cmsgate\hutkigrosh;


use esas\cmsgate\Registry;
use esas\cmsgate\hutkigrosh\view\client\CompletionPanelHutkigrosh;
use esas\cmsgate\hutkigrosh\wrappers\ConfigWrapperHutkigrosh;

/**
 * Реализация шаблона registry для удобства доступа к $configWrapper и $translator.
 * В каждой CMS должен быть обязательно наследован и проинициализирован через Registry->init()
 * Class Registry
 * @package esas\cmsgate
 */
abstract class RegistryHutkigrosh extends Registry
{
    /**
     * @return RegistryHutkigrosh
     */
    public static function getRegistry()
    {
        return parent::getRegistry();
    }

    /**
     * @return ConfigWrapperHutkigrosh
     */
    public function getConfigWrapper()
    {
        return parent::getConfigWrapper();
    }

    public function getCompletionPanel($orderWrapper)
    {
        $completionPanel = new CompletionPanelHutkigrosh($orderWrapper);
        return $completionPanel;
    }

    public function getPaySystemName() {
        return "hutkigrosh";
    }

    abstract function getUrlAlfaclick($orderId);

    abstract function getUrlWebpay($orderId);

}