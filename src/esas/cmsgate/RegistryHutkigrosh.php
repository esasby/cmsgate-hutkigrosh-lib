<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 01.10.2018
 * Time: 11:35
 */

namespace esas\cmsgate;


use esas\cmsgate\lang\Translator;
use esas\cmsgate\utils\Logger;
use esas\cmsgate\view\admin\ConfigForm;
use esas\cmsgate\view\admin\ManagedFieldsHutkigrosh;
use esas\cmsgate\view\client\CompletionPanel;
use esas\cmsgate\wrappers\ConfigWrapperHutkigrosh;
use esas\cmsgate\wrappers\OrderWrapper;

/**
 * Реализация шаблона registry для удобства доступа к $configurationWrapper и $translator.
 * В каждой CMS должен быть обязательно наследован и проинициализирован через Registry->init()
 * Class Registry
 * @package esas\cmsgate
 */
abstract class RegistryHutkigrosh extends Registry
{
    public function getCompletionPanel($orderWrapper)
    {
        $completionPanel = new CompletionPanel($orderWrapper);
        return $completionPanel;
    }


}