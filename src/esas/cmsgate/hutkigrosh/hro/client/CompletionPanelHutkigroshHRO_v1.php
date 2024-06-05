<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 10.10.2018
 * Time: 11:27
 */

namespace esas\cmsgate\hutkigrosh\hro\client;


use esas\cmsgate\hro\accordions\AccordionHRO;
use esas\cmsgate\hro\accordions\AccordionHROFactory;
use esas\cmsgate\hro\accordions\AccordionTabHROFactory;
use esas\cmsgate\hro\panels\MessagesPanelHROFactory;
use esas\cmsgate\hutkigrosh\utils\ResourceUtilsHutkigrosh;
use esas\cmsgate\hutkigrosh\view\client\ClientViewFieldsHutkigrosh;
use esas\cmsgate\lang\Translator;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;

/**
 * Class CompletionPanelHutkigrosh используется для формирования итоговой страницы. Основной класс
 * для темазависимого представления (HGCMS-23).
 * Разбит на множество мелких методов для возможности легкого переопрделения. Что позволяет формировать итоговоую
 * страницу в тегах и CSS-классах принятых в конкретных CMS
 * @package esas\hutkigrosh\view\client
 */
class CompletionPanelHutkigroshHRO_v1 implements CompletionPanelHutkigroshHRO
{
    /**
     * Флаг, когда только один таб
     * и не нужна возможность выбора (например при renderWebpayOnly)
     * @var bool
     */
    private $onlyOneTab = false;

    protected $completionText;
    protected $instructionText;
    protected $webpayForm;
    protected $webpayStatus;
    protected $qrCode;
    /**
     * @var boolean
     */
    protected $instructionSectionEnable;
    /**
     * @var boolean
     */
    protected $qrcodeSectionEnable;
    /**
     * @var boolean
     */
    protected $webpaySectionEnable;
    /**
     * @var boolean
     */
    protected $alfaclickSectionEnable;

    protected $additionalCSSFile;

    /**
     * @var bool
     */
    protected $orderCanBePayed = false;

    protected $alfaclickUrl;
    protected $alfaclickBillId;
    protected $alfaclickPhone;

    /**
     * @param mixed $completionText
     * @return CompletionPanelHutkigroshHRO_v1
     */
    public function setCompletionText($completionText) {
        $this->completionText = $completionText;
        return $this;
    }

    /**
     * @param mixed $instructionText
     * @return CompletionPanelHutkigroshHRO_v1
     */
    public function setInstructionText($instructionText) {
        $this->instructionText = $instructionText;
        return $this;
    }

    /**
     * @param mixed $qrCode
     * @return CompletionPanelHutkigroshHRO_v1
     */
    public function setQrCode($qrCode) {
        $this->qrCode = $qrCode;
        return $this;
    }

    /**
     * @param mixed $webpayForm
     * @return CompletionPanelHutkigroshHRO_v1
     */
    public function setWebpayForm($webpayForm) {
        $this->webpayForm = $webpayForm;
        return $this;
    }

    /**
     * @param mixed $webpayStatus
     * @return CompletionPanelHutkigroshHRO_v1
     */
    public function setWebpayStatus($webpayStatus) {
        $this->webpayStatus = $webpayStatus;
        return $this;
    }

    /**
     * @param bool $instructionSectionEnable
     * @return CompletionPanelHutkigroshHRO_v1
     */
    public function setInstructionsSectionEnabled($instructionSectionEnable) {
        $this->instructionSectionEnable = $instructionSectionEnable;
        return $this;
    }

    /**
     * @param bool $qrcodeSectionEnable
     * @return CompletionPanelHutkigroshHRO_v1
     */
    public function setQrcodeSectionEnabled($qrcodeSectionEnable) {
        $this->qrcodeSectionEnable = $qrcodeSectionEnable;
        return $this;
    }

    /**
     * @param bool $webpaySectionEnable
     * @return CompletionPanelHutkigroshHRO_v1
     */
    public function setWebpaySectionEnabled($webpaySectionEnable) {
        $this->webpaySectionEnable = $webpaySectionEnable;
        return $this;
    }

    /**
     * @param bool $alfaclickSectionEnable
     * @return CompletionPanelHutkigroshHRO_v1
     */
    public function setAlfaclickSectionEnabled($alfaclickSectionEnable) {
        $this->alfaclickSectionEnable = $alfaclickSectionEnable;
        return $this;
    }

    /**
     * Used in alfaclickJs
     * @return mixed
     */
    public function getAlfaclickUrl() {
        return $this->alfaclickUrl;
    }

    /**
     * @param mixed $alfaclickUrl
     * @return CompletionPanelHutkigroshHRO_v1
     */
    public function setAlfaclickUrl($alfaclickUrl) {
        $this->alfaclickUrl = $alfaclickUrl;
        return $this;
    }

    /**
     * @param mixed $alfaclickBillId
     * @return CompletionPanelHutkigroshHRO_v1
     */
    public function setAlfaclickBillId($alfaclickBillId) {
        $this->alfaclickBillId = $alfaclickBillId;
        return $this;
    }

    /**
     * @param mixed $alfaclickPhone
     * @return CompletionPanelHutkigroshHRO_v1
     */
    public function setAlfaclickPhone($alfaclickPhone) {
        $this->alfaclickPhone = $alfaclickPhone;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setOrderCanBePayed($orderCanBePayed) {
        $this->orderCanBePayed = $orderCanBePayed;
        return $this;
    }

    public function build() {
        if (!$this->orderCanBePayed) {
            return MessagesPanelHROFactory::findBuilder()->build();
        }
        $this->onlyOneTab = false;
        return element::content(
            element::div(
                attribute::id("completion-text"),
                attribute::clazz($this->getCssClass4CompletionTextDiv()),
                element::content($this->completionText)
            ),
            $this->elementTabs(),
            $this->addCss()
        );
    }

    const TABS_ID = "hutkigrosh-completion-tabs";

    public function renderWebpayOnly() {
        if (!$this->orderCanBePayed) {
            return MessagesPanelHROFactory::findBuilder()->build();
        }
        $completionPanel =
            $this->elementTab(
                self::TAB_KEY_WEBPAY,
                $this->getWebpayTabLabel(),
                $this->elementWebpayTabContent(),
                false
            );
        echo $completionPanel;
    }

    public function redirectWebpay() {
        if (!$this->orderCanBePayed) {
            echo MessagesPanelHROFactory::findBuilder()->build();
        }
        $this->onlyOneTab = true;
        $completionPanel = element::content(
            $this->elementTab(
                self::TAB_KEY_WEBPAY,
                $this->getWebpayTabLabel(),
                $this->elementWebpayTabContent(),
                false
            ),
            element::includeFile(dirname(__FILE__) . "/webpayAutoSubmitJs.php", ["completionPanel" => $this])

        );
        echo $completionPanel;
    }

    public function elementTabs() {
        $accordion = $this->accordionBuilder()
            ->setId(self::TABS_ID)
            ->addTab($this->elementInstructionsTab())
            ->addTab($this->elementQRCodeTab())
            ->addTab($this->elementWebpayTab())
            ->addTab($this->elementAlfaclickTab());
        return $accordion->build();
    }

    /**
     * @return AccordionHRO
     */
    protected function accordionBuilder() {
        return AccordionHROFactory::findBuilder();
    }

    public function addCss() {
        return array(
            element::styleFile($this->additionalCSSFile)
        );
    }

    /**
     * @return string
     */
    public function getInstructionsTabLabel() {
        return Translator::fromRegistry()->translate(ClientViewFieldsHutkigrosh::INSTRUCTIONS_TAB_LABEL);
    }

    /**
     * @return string
     */
    public function getQRCodeTabLabel() {
        return Translator::fromRegistry()->translate(ClientViewFieldsHutkigrosh::QRCODE_TAB_LABEL);
    }

    /**
     * @return string
     */
    public function getQRCodeDetails() {
        return strtr(Translator::fromRegistry()->translate(ClientViewFieldsHutkigrosh::QRCODE_DETAILS), array(
            "@qr_code" => $this->qrCode
        ));
    }

    /**
     * @return string
     */
    public function getWebpayTabLabel() {
        return Translator::fromRegistry()->translate(ClientViewFieldsHutkigrosh::WEBPAY_TAB_LABEL);
    }

    /**
     * @return string
     */
    public function getAlfaclickTabLabel() {
        return Translator::fromRegistry()->translate(ClientViewFieldsHutkigrosh::ALFACLICK_TAB_LABEL);
    }

    /**
     * @return string
     */
    public function getAlfaclickButtonLabel() {
        return Translator::fromRegistry()->translate(ClientViewFieldsHutkigrosh::ALFACLICK_BUTTON_LABEL);
    }

    /**
     * @return string
     */
    public function getAlfaclickDetails() {
        return Translator::fromRegistry()->translate(ClientViewFieldsHutkigrosh::ALFACLICK_DETAILS);
    }

    /**
     * @return string
     */
    public function getAlfaclickMsgSuccess() {
        return Translator::fromRegistry()->translate(ClientViewFieldsHutkigrosh::ALFACLICK_MSG_SUCCESS);
    }

    /**
     * @return string
     */
    public function getAlfaclickMsgUnsuccess() {
        return Translator::fromRegistry()->translate(ClientViewFieldsHutkigrosh::ALFACLICK_MSG_UNSUCCESS);
    }


    public function elementTab($key, $header, $body, $selectable = true) {
        return AccordionTabHROFactory::findBuilder()
            ->setChecked($this->isTabChecked($key))
            ->setHeader($header)
            ->setBody($body)
            ->setKey($key);
    }

    public function isTabChecked($tabKey) {
        if ($this->isOnlyOneTabEnabled())
            return true;
        $webpayStatusPresent = '' != $this->webpayStatus;
        switch ($tabKey) {
            case self::TAB_KEY_INSTRUCTIONS:
                return !$webpayStatusPresent;
            case self::TAB_KEY_WEBPAY:
                return $webpayStatusPresent;
            default:
                return false;
        }
    }

    public function isOnlyOneTabEnabled() {
        if ($this->onlyOneTab)
            return true;
        $enabledTabsCount = 0;
        if ($this->instructionSectionEnable)
            $enabledTabsCount++;
        if ($this->qrcodeSectionEnable)
            $enabledTabsCount++;
        if ($this->webpaySectionEnable)
            $enabledTabsCount++;
        if ($this->alfaclickSectionEnable)
            $enabledTabsCount++;
        return $enabledTabsCount == 1;
    }

    const TAB_KEY_WEBPAY = "webpay";
    const TAB_KEY_INSTRUCTIONS = "instructions";
    const TAB_KEY_QRCODE = "qrcode";
    const TAB_KEY_ALFACLICK = "alfaclick";

    public function elementWebpayTab() {
        if ($this->webpaySectionEnable) {
            return $this->elementTab(
                self::TAB_KEY_WEBPAY,
                $this->getWebpayTabLabel(),
                $this->elementWebpayTabContent());
        }
        return "";
    }

    public function elementInstructionsTab() {
        if ($this->instructionSectionEnable) {
            return $this->elementTab(
                self::TAB_KEY_INSTRUCTIONS,
                $this->getInstructionsTabLabel(),
                $this->instructionText);
        }
        return "";
    }

    public function elementQRCodeTab() {
        if ($this->qrcodeSectionEnable) {
            return $this->elementTab(
                self::TAB_KEY_QRCODE,
                $this->getQRCodeTabLabel(),
                $this->getQRCodeDetails());
        }
        return "";
    }

    public function elementAlfaclickTab() {
        if ($this->alfaclickSectionEnable) {
            return $this->elementTab(
                self::TAB_KEY_ALFACLICK,
                $this->getAlfaclickTabLabel(),
                $this->elementAlfaclickTabContent2());
        }
        return "";
    }

    public function setAdditionalCSSFile($fileName) {
        if ("default" == $fileName)
            $this->additionalCSSFile = dirname(__FILE__) . "/completion-default.css";
        else if (!empty($fileName))
            $this->additionalCSSFile = $_SERVER['DOCUMENT_ROOT'] . $fileName;
        return $this;
    }

    const STATUS_PAYED = 'payed';
    const STATUS_FAILED = 'failed';

    public function elementWebpayTabContent() {
        $ret =
            element::div(
                attribute::id("webpay_details"),
                element::content(Translator::fromRegistry()->translate(ClientViewFieldsHutkigrosh::WEBPAY_DETAILS)),
                element::br());

        $ret .= $this->elementWebpayTabContentResultMsg($this->webpayStatus);

        if ("" != $this->webpayForm) {
            $ret .=
                element::div(
                    attribute::id("webpay"),
                    attribute::align("right"),
                    element::img(
                        attribute::id("webpay-ps-image"),
                        attribute::src(ResourceUtilsHutkigrosh::getPsImageUrl()),
                        attribute::alt("")
                    ),
                    element::br(),
                    element::content($this->webpayForm),
                    element::includeFile(dirname(__FILE__) . "/webpayJs.php", ["completionPanel" => $this]));
        } else {
            $ret .=
                element::div(
                    attribute::id("webpay_message_unavailable"),
                    attribute::clazz($this->getCssClass4MsgUnsuccess()),
                    element::content(Translator::fromRegistry()->translate(ClientViewFieldsHutkigrosh::WEBPAY_MSG_UNAVAILABLE)));
        }
        return $ret;
    }

    public function elementWebpayTabContentResultMsg($status) {
        if (self::STATUS_PAYED == $status) {
            return
                element::div(
                    attribute::clazz($this->getCssClass4MsgSuccess()),
                    attribute::id("webpay_message"),
                    element::content(Translator::fromRegistry()->translate(ClientViewFieldsHutkigrosh::WEBPAY_MSG_SUCCESS)));
        } elseif (self::STATUS_FAILED == $status) {
            return
                element::div(
                    attribute::clazz($this->getCssClass4MsgUnsuccess()),
                    attribute::id("webpay_message"),
                    element::content(Translator::fromRegistry()->translate(ClientViewFieldsHutkigrosh::WEBPAY_MSG_UNSUCCESS)));
        } else
            return "";
    }

    const ALFACLICK_URL = "alfaclickurl";

    public function elementAlfaclickTabContent() {
        return
            element::content(
                element::div(
                    attribute::id("alfaclick_details"),
                    element::content($this->getAlfaclickDetails()),
                    element::br()),
                $this->elementAlfaclickTabContentForm(),
                element::includeFile(dirname(__FILE__) . "/alfaclickJs.php", ["completionPanel" => $this]));
    }

    public function elementAlfaclickTabContentForm() {
        return
            element::div(
                attribute::id("alfaclick_form"),
                attribute::clazz($this->getCssClass4AlfaclickForm()),
                attribute::align("right"),
                element::input(
                    attribute::id("billID"),
                    attribute::type('hidden'),
                    attribute::value($this->alfaclickBillId)),
                element::input(
                    attribute::id("phone"),
                    attribute::type('tel'),
                    attribute::value($this->alfaclickPhone),
                    attribute::clazz($this->getCssClass4FormInput()),
                    attribute::maxlength('20')),
                element::a(
                    attribute::clazz("hutkigrosh-button " . $this->getCssClass4AlfaclickButton()),
                    attribute::id("alfaclick_button"),
                    element::content($this->getAlfaclickButtonLabel()))
            );
    }

    public function elementAlfaclickTabContent2()
    {
        return
            element::content(
                element::div(
                    attribute::clazz("row mb-3"),
                    attribute::id("alfaclick_details"),
                    element::label(
                        attribute::forr("phone"),
                        attribute::clazz("col-md-4 col-form-label"),
                        element::content($this->getAlfaclickDetails())
                    ),
                    element::div(
                        attribute::clazz("col-md-8"),
                        element::input(
                            attribute::id("billID"),
                            attribute::type('hidden'),
                            attribute::value($this->alfaclickBillId)),
                        element::input(
                            attribute::id("phone"),
                            attribute::type('tel'),
                            attribute::clazz($this->getCssClass4FormInput()),
                            attribute::value($this->alfaclickPhone)
                        )
                    )
                ),
                element::div(
                    attribute::clazz("text-end"),
                    element::button(
                        attribute::id("alfaclick_button"),
                        attribute::clazz("hutkigrosh-button " . $this->getCssClass4Button()),
                        attribute::type("button"),
                        element::content($this->getAlfaclickButtonLabel())
                    )
                ),
                element::includeFile(dirname(__FILE__) . "/alfaclickJs.php", ["completionPanel" => $this])
            );
    }

    /**
     * @return string
     */
    public function getCssClass4MsgSuccess()
    {
        return "";
    }

    /**
     * @return string
     */
    public function getCssClass4MsgUnsuccess() {
        return "";
    }

    /**
     * @return string
     */
    public function getCssClass4CompletionTextDiv() {
        return "";
    }

    /**
     * @return string
     */
    public function getCssClass4AlfaclickButton() {
        return $this->getCssClass4Button();
    }

    /**
     * used in webpayJs.php
     * @return string
     */
    public function getCssClass4WebpayButton() {
        return $this->getCssClass4Button();
    }

    /**
     * @return string
     */
    public function getCssClass4Button() {
        return "";
    }

    public function getCssClass4AlfaclickForm() {
        return "";
    }

    public function getCssClass4FormInput() {
        return "";
    }

    public static function builder() {
        return new CompletionPanelHutkigroshHRO_v1();
    }
}