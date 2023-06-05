<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 04.07.2019
 * Time: 12:07
 */

namespace esas\cmsgate\hutkigrosh\controllers;


use esas\cmsgate\controllers\Controller;
use esas\cmsgate\Registry;
use esas\cmsgate\hutkigrosh\RegistryHutkigrosh;
use esas\cmsgate\hutkigrosh\wrappers\ConfigWrapperHutkigrosh;

abstract class ControllerHutkigrosh extends Controller
{
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
    }


}