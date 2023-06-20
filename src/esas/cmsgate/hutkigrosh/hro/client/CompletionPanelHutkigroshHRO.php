<?php


namespace esas\cmsgate\hutkigrosh\hro\client;


use esas\cmsgate\hro\HRO;

interface CompletionPanelHutkigroshHRO extends HRO
{
    /**
     * @param boolean $enabled
     * @return CompletionPanelHutkigroshHRO
     */
    public function setInstructionsSectionEnabled($enabled);

    /**
     * @param mixed $instructionText
     * @return CompletionPanelHutkigroshHRO
     */
    public function setInstructionText($instructionText);

    /**
     * @param boolean $enabled
     * @return CompletionPanelHutkigroshHRO
     */
    public function setWebpaySectionEnabled($enabled);

    /**
     * @param mixed $completionText
     * @return CompletionPanelHutkigroshHRO
     */
    public function setCompletionText($completionText);

    /**
     * @param mixed $webpayForm
     * @return CompletionPanelHutkigroshHRO
     */
    public function setWebpayForm($webpayForm);

    /**
     * @param mixed $webpayStatus
     * @return CompletionPanelHutkigroshHRO
     */
    public function setWebpayStatus($webpayStatus);

    /**
     * @param boolean $enabled
     * @return CompletionPanelHutkigroshHRO
     */
    public function setQRCodeSectionEnabled($enabled);

    /**
     * @param boolean $enabled
     * @return CompletionPanelHutkigroshHRO
     */
    public function setAlfaclickSectionEnabled($enabled);

    /**
     * @param string $alfaclickUrl
     * @return CompletionPanelHutkigroshHRO
     */
    public function setAlfaclickUrl($alfaclickUrl);

    /**
     * @param string $alfaclickBillId
     * @return CompletionPanelHutkigroshHRO
     */
    public function setAlfaclickBillId($alfaclickBillId);

    /**
     * @param string $alfaclickPhone
     * @return CompletionPanelHutkigroshHRO
     */
    public function setAlfaclickPhone($alfaclickPhone);

    /**
     * @param mixed $qrCode
     * @return CompletionPanelHutkigroshHRO
     */
    public function setQrCode($qrCode);

    /**
     * @param mixed $fileName
     * @return CompletionPanelHutkigroshHRO
     */
    public function setAdditionalCSSFile($fileName);

    /**
     * @param bool $orderCanBePayed
     * @return CompletionPanelHutkigroshHRO
     */
    public function setOrderCanBePayed($orderCanBePayed);

    public function renderWebpayOnly();

    public function redirectWebpay();
}