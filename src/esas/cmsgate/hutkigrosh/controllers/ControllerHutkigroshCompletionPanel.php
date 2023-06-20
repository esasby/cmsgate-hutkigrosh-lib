<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 22.03.2018
 * Time: 14:13
 */

namespace esas\cmsgate\hutkigrosh\controllers;

use esas\cmsgate\hutkigrosh\hro\client\CompletionPanelHutkigroshHRO;
use esas\cmsgate\hutkigrosh\hro\client\CompletionPanelHutkigroshHROFactory;
use esas\cmsgate\hutkigrosh\RegistryHutkigrosh;
use esas\cmsgate\hutkigrosh\utils\QRUtils;
use esas\cmsgate\hutkigrosh\utils\RequestParamsHutkigrosh;
use esas\cmsgate\hutkigrosh\view\client\ClientViewFieldsHutkigrosh;
use esas\cmsgate\hutkigrosh\wrappers\ConfigWrapperHutkigrosh;
use esas\cmsgate\lang\Translator;
use esas\cmsgate\messenger\Messenger;
use esas\cmsgate\Registry;
use esas\cmsgate\view\client\ClientViewFields;
use esas\cmsgate\wrappers\OrderWrapper;
use Exception;
use Throwable;

class ControllerHutkigroshCompletionPanel extends ControllerHutkigrosh
{
    /**
     * @param int|OrderWrapper $orderWrapper
     * @return CompletionPanelHutkigroshHRO
     * @throws Throwable
     */
    public function process($orderWrapper) {
        try {
            if (is_numeric($orderWrapper)) //если передан orderId
                $orderWrapper = $this->registry->getOrderWrapperByOrderNumberOrId($orderWrapper);
            $loggerMainString = "Order[" . $orderWrapper->getOrderNumberOrId() . "]: ";
            $this->logger->info($loggerMainString . "Controller started");
            $completionPanel = CompletionPanelHutkigroshHROFactory::findBuilder();
            $configWrapper = ConfigWrapperHutkigrosh::fromRegistry();

            switch ($orderWrapper->getStatus()->getOrderStatus()) {
                case Registry::getRegistry()->getConfigWrapper()->getOrderStatusPayed():
                    Messenger::fromRegistry()->addSuccessMessage(
                        Registry::getRegistry()->getConfigWrapper()->cookText(
                            Translator::fromRegistry()->translate(ClientViewFields::COMPLETION_PAGE_ORDER_PAYED_ALERT), $orderWrapper));
                    return $completionPanel;
                case Registry::getRegistry()->getConfigWrapper()->getOrderStatusFailed():
                    Messenger::fromRegistry()->addErrorMessage(
                        Registry::getRegistry()->getConfigWrapper()->cookText(
                            Translator::fromRegistry()->translate(ClientViewFields::COMPLETION_PAGE_ORDER_FAILED_ALERT), $orderWrapper));
                    return $completionPanel;
                case Registry::getRegistry()->getConfigWrapper()->getOrderStatusCanceled():
                    Messenger::fromRegistry()->addWarnMessage(
                        Registry::getRegistry()->getConfigWrapper()->cookText(
                            Translator::fromRegistry()->translate(ClientViewFields::COMPLETION_PAGE_ORDER_CANCELED_ALERT), $orderWrapper));
                    return $completionPanel;
                case Registry::getRegistry()->getConfigWrapper()->getOrderStatusPending():
                    $completionPanel->setOrderCanBePayed(true);
                    break;
                default:
                    $this->logger->error($loggerMainString . 'Unknown order status[' . $orderWrapper->getStatus()->getOrderStatus() . ']');
                    Messenger::fromRegistry()->addWarnMessage(
                        Registry::getRegistry()->getConfigWrapper()->cookText(
                            Translator::fromRegistry()->translate(ClientViewFields::COMPLETION_PAGE_ORDER_UNKNOWN_STATUS_ALERT), $orderWrapper));
                    return $completionPanel;
            }

            $completionPanel
                ->setCompletionText($configWrapper->cookText($configWrapper->getCompletionText(), $orderWrapper))
                ->setInstructionsSectionEnabled($configWrapper->isInstructionsSectionEnabled())
                ->setQRCodeSectionEnabled($configWrapper->isQRCodeSectionEnabled())
                ->setWebpaySectionEnabled($configWrapper->isWebpaySectionEnabled())
                ->setAlfaclickSectionEnabled($configWrapper->isAlfaclickSectionEnabled())
                ->setAdditionalCSSFile($configWrapper->getCompletionCssFile());
            if ($configWrapper->isInstructionsSectionEnabled())
                $completionPanel->setInstructionText($configWrapper->cookText(Translator::fromRegistry()->translate(ClientViewFieldsHutkigrosh::INSTRUCTIONS), $orderWrapper));
            if ($configWrapper->isQRCodeSectionEnabled())
                $completionPanel->setQrCode(QRUtils::getEripBillQR($orderWrapper));
            if ($configWrapper->isAlfaclickSectionEnabled()) {
                $completionPanel
                    ->setAlfaclickUrl(RegistryHutkigrosh::getRegistry()->getUrlAlfaclick($orderWrapper))
                    ->setAlfaclickBillId($orderWrapper->getExtId())
                    ->setAlfaclickPhone($orderWrapper->getMobilePhone());
            }
            if ($configWrapper->isWebpaySectionEnabled()) {
                $controller = new ControllerHutkigroshWebpayForm();
                $webpayResp = $controller->process($orderWrapper);
                $completionPanel->setWebpayForm($webpayResp->getHtmlForm());
                if (array_key_exists(RequestParamsHutkigrosh::WEBPAY_STATUS, $_REQUEST))
                    $completionPanel->setWebpayStatus($_REQUEST[RequestParamsHutkigrosh::WEBPAY_STATUS]);
            }
            return $completionPanel;
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