<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 04.07.2019
 * Time: 12:07
 */

namespace esas\cmsgate\controllers;


use esas\cmsgate\Registry;
use esas\cmsgate\RegistryHutkigrosh;
use esas\cmsgate\wrappers\ConfigWrapperHutkigrosh;

abstract class ControllerHutkigrosh extends Controller
{
    /**
     * @var ConfigWrapperHutkigrosh
     */
    protected $configWrapper;

    /**
     * @var RegistryHutkigrosh
     */
    protected $registry;

    /**
     * ControllerHutkigrosh constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->registry = Registry::getRegistry();
        $this->configWrapper = Registry::getRegistry()->getConfigWrapper();
    }


}