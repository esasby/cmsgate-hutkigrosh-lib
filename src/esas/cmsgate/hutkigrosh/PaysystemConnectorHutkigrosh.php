<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 14.04.2020
 * Time: 13:45
 */

namespace esas\cmsgate\hutkigrosh;


use esas\cmsgate\descriptors\PaySystemConnectorDescriptor;
use esas\cmsgate\descriptors\VendorDescriptor;
use esas\cmsgate\descriptors\VersionDescriptor;
use esas\cmsgate\hutkigrosh\controllers\ControllerHutkigroshLogin;
use esas\cmsgate\hutkigrosh\lang\TranslatorHutkigrosh;
use esas\cmsgate\hutkigrosh\view\admin\ManagedFieldsFactoryHutkigrosh;
use esas\cmsgate\hutkigrosh\wrappers\ConfigWrapperHutkigrosh;
use esas\cmsgate\PaysystemConnector;
use esas\cmsgate\view\admin\ManagedFieldsFactory;

class PaysystemConnectorHutkigrosh extends PaysystemConnector
{
    public function createConfigWrapper()
    {
        return new ConfigWrapperHutkigrosh();
    }

    public function createTranslator()
    {
        return new TranslatorHutkigrosh();
    }

    /**
     * @return ManagedFieldsFactory
     */
    public function createManagedFieldsFactory()
    {
        return new ManagedFieldsFactoryHutkigrosh();
    }

    public function createPaySystemConnectorDescriptor()
    {
        return new PaySystemConnectorDescriptor(
            "cmsgate-hutkigrosh-lib",
            new VersionDescriptor("v2.0.3", "2023-06-23"),
            "Hutkigrosh (ERIP Belarus) cmsgate connector",
            "www.hutkigrosh.by",
            VendorDescriptor::esas(),
            "hutkigrosh"
        );
    }

    public function createHooks()
    {
        return new HooksHutkigrosh();
    }

    public function checkAuth($login, $password, $sandbox)
    {
        $controllre = new ControllerHutkigroshLogin();
        $controllre->process($login, $password, $sandbox);
    }
}