<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 22.03.2018
 * Time: 14:13
 */

namespace esas\cmsgate\hutkigrosh\controllers;

use esas\cmsgate\ConfigStorageCmsArray;
use esas\cmsgate\hutkigrosh\ConfigFieldsHutkigrosh;
use esas\cmsgate\hutkigrosh\wrappers\ConfigWrapperHutkigrosh;
use esas\cmsgate\hutkigrosh\protocol\HutkigroshProtocol;
use esas\cmsgate\Registry;
use Exception;
use Throwable;

class ControllerHutkigroshLogin extends ControllerHutkigrosh
{
    /**
     * @return boolean
     * @throws Throwable
     */
    public function process($login, $password, $sandbox = true)
    {
        try {
            $loggerMainString = "Login[" . $login . "]: ";
            $this->logger->info($loggerMainString . "Controller started");
            $configWrapper =
                new ConfigWrapperHutkigrosh(
                    new ConfigStorageCmsArray([
                        ConfigFieldsHutkigrosh::login() => $login,
                        ConfigFieldsHutkigrosh::password() => $password,
                        ConfigFieldsHutkigrosh::sandbox() => $sandbox,
                        ConfigFieldsHutkigrosh::debugMode() => false])
                );
            $hg = new HutkigroshProtocol($configWrapper);
            $resp = $hg->apiLogIn();
            if ($resp->hasError()) {
                $hg->apiLogOut();
                throw new Exception($resp->getResponseMessage(), $resp->getResponseCode());
            }
            $this->logger->info($loggerMainString . "Password is correct!");
            return true;
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