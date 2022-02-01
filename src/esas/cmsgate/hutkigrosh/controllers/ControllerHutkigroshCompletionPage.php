<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 22.03.2018
 * Time: 14:13
 */

namespace esas\cmsgate\hutkigrosh\controllers;

use esas\cmsgate\hutkigrosh\view\client\CompletionPageHutkigrosh;
use esas\cmsgate\Registry;
use esas\cmsgate\wrappers\OrderWrapper;
use Exception;
use Throwable;

class ControllerHutkigroshCompletionPage extends ControllerHutkigrosh
{
    /**
     * @param int|OrderWrapper $orderWrapper
     * @return CompletionPageHutkigrosh
     * @throws Throwable
     */
    public function process($orderWrapper)
    {
        try {
            if (is_numeric($orderWrapper)) //если передан orderId
                $orderWrapper = $this->registry->getOrderWrapperByOrderNumberOrId($orderWrapper);
            $loggerMainString = "Order[" . $orderWrapper->getOrderNumberOrId() . "]: ";
            $this->logger->info($loggerMainString . "Controller started");

            $controller = new ControllerHutkigroshCompletionPanel();
            $completionPanel = $controller->process($orderWrapper);
            $completionPanel = $completionPanel->__toString();

            $completionPage = $this->registry->getCompletionPage($orderWrapper, $completionPanel);
            return $completionPage;
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