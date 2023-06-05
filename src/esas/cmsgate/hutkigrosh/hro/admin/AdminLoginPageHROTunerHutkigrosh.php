<?php


namespace esas\cmsgate\hutkigrosh\hro\admin;


use esas\cmsgate\hutkigrosh\view\admin\AdminViewFieldsHutkigrosh;
use esas\cmsgate\Registry;
use esas\cmsgate\hro\HRO;
use esas\cmsgate\hro\HROTuner;
use esas\cmsgate\hro\pages\AdminLoginPageHRO;

class AdminLoginPageHROTunerHutkigrosh implements HROTuner
{
    /**
     * @param AdminLoginPageHRO $hroBuilder
     * @return HRO|void
     */
    public function tune($hroBuilder) {
        return $hroBuilder
            ->setLoginField(AdminViewFieldsHutkigrosh::LOGIN_FORM_LOGIN, "Login")
            ->setPasswordField(AdminViewFieldsHutkigrosh::LOGIN_FORM_PASSWORD, 'Password')
            ->setMessage("Login to cmsgate " . Registry::getRegistry()->getPaysystemConnector()->getPaySystemConnectorDescriptor()->getPaySystemMachinaName());
    }
}