<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 10.10.2018
 * Time: 11:27
 */

namespace esas\cmsgate\hutkigrosh\view\client;


use esas\cmsgate\hutkigrosh\wrappers\ConfigWrapperHutkigrosh;
use esas\cmsgate\lang\Translator;
use esas\cmsgate\Registry;
use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;
use esas\cmsgate\utils\Logger;
use esas\cmsgate\hutkigrosh\utils\QRUtils;
use esas\cmsgate\utils\ResourceUtils;
use esas\cmsgate\wrappers\OrderWrapper;

/**
 * Class CompletionPanelHutkigrosh используется для формирования итоговой страницы. Основной класс
 * для темазависимого представления (HGCMS-23).
 * Разбит на множество мелких методов для возможности легкого переопрделения. Что позволяет формировать итоговоую
 * страницу в тегах и CSS-классах принятых в конкретных CMS
 * @package esas\hutkigrosh\view\client
 */
class CompletionPanelHutkigrosh
{
    /**
     * @var Logger
     */
    protected $logger;
    /**
     * @var ConfigWrapperHutkigrosh
     */
    private $configWrapper;

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var OrderWrapper
     */
    private $orderWrapper;

    private $webpayForm;
    private $webpayStatus;

    /**
     * @var bool
     */
    private $alfaclickUrl;

    /**
     * ViewData constructor.
     * @param OrderWrapper $orderWrapper
     */
    public function __construct($orderWrapper)
    {
        $this->logger = Logger::getLogger(get_class($this));
        $this->configWrapper = Registry::getRegistry()->getConfigWrapper();
        $this->translator = Registry::getRegistry()->getTranslator();
        $this->orderWrapper = $orderWrapper;
    }

    public function __toString()
    {
        $completionPanel = element::content(
            element::div(
                attribute::id("completion-text"),
                attribute::clazz($this->getCssClass4CompletionTextDiv()),
                element::content($this->getCompletionText())
            ),
            element::div(
                attribute::id("hutkigrosh-completion-tabs"),
                attribute::clazz($this->getCssClass4TabsGroup()),
                $this->addTabs()),
            $this->addCss()
        );
        return $completionPanel->__toString();
    }


    public function render()
    {
        echo $this->__toString();
    }

    public function renderWebpayOnly()
    {
        $completionPanel =
            $this->elementTab(
                self::TAB_KEY_WEBPAY,
                $this->getWebpayTabLabel(),
                $this->elementWebpayTabContent($this->getWebpayStatus(), $this->getWebpayForm()),
                false
            );
        echo $completionPanel;
    }

    public function redirectWebpay()
    {
        $completionPanel = element::content(
            $this->elementTab(
                self::TAB_KEY_WEBPAY,
                $this->getWebpayTabLabel(),
                $this->elementWebpayTabContent($this->getWebpayStatus(), $this->getWebpayForm()),
                false
            ),
            element::includeFile(dirname(__FILE__) . "/webpayAutoSubmitJs.php", ["completionPanel" => $this])

        );
        echo $completionPanel;
    }

    public function addTabs()
    {
        return array(
            $this->elementInstructionsTab(),
            $this->elementQRCodeTab(),
            $this->elementWebpayTab(),
            $this->elementAlfaclickTab()
        );
    }

    public function addCss()
    {
        return array(
            element::styleFile($this->getCoreCSSFilePath()), // CSS для аккордеона, общий для всех
            element::styleFile($this->getModuleCSSFilePath()), // CSS, специфичный для модуля
            element::styleFile($this->getAdditionalCSSFilePath())
        );
    }

    /**
     * @return string
     */
    public function getInstructionsTabLabel()
    {
        return $this->translator->translate(ClientViewFieldsHutkigrosh::INSTRUCTIONS_TAB_LABEL);
    }

    /**
     * @return string
     */
    public function getInstructionsText()
    {
        return $this->configWrapper->cookText($this->translator->translate(ClientViewFieldsHutkigrosh::INSTRUCTIONS), $this->orderWrapper);
    }


    /**
     * @return string
     */
    public function getCompletionText()
    {
        return $this->configWrapper->cookText($this->configWrapper->getCompletionText(), $this->orderWrapper);
    }

    /**
     * @return bool
     */
    public function isInstructionsSectionEnabled()
    {
        return $this->configWrapper->isInstructionsSectionEnabled();
    }

    /**
     * @return bool
     */
    public function isWebpaySectionEnabled()
    {
        return $this->configWrapper->isWebpaySectionEnabled();
    }

    /**
     * @return bool
     */
    public function isQRCodeSectionEnabled()
    {
        return $this->configWrapper->isQRCodeSectionEnabled();
    }

    /**
     * @return string
     */
    public function getQRCodeTabLabel()
    {
        return $this->translator->translate(ClientViewFieldsHutkigrosh::QRCODE_TAB_LABEL);
    }

    /**
     * @return string
     */
    public function getQRCodeDetails()
    {
        return strtr($this->translator->translate(ClientViewFieldsHutkigrosh::QRCODE_DETAILS), array(
            "@qr_code" => QRUtils::getEripBillQR($this->orderWrapper)
        ));
    }

    /**
     * @return mixed
     */
    public function getWebpayForm()
    {
        return $this->webpayForm;
    }

    /**
     * @param mixed $webpayForm
     */
    public function setWebpayForm($webpayForm)
    {
        $this->webpayForm = $webpayForm;
    }

    /**
     * @return string
     */
    public function getWebpayStatus()
    {
        return $this->webpayStatus;
    }

    /**
     * @param mixed $webpayStatus
     */
    public function setWebpayStatus($webpayStatus)
    {
        $this->webpayStatus = $webpayStatus;
    }

    /**
     * @return string
     */
    public function getWebpayTabLabel()
    {
        return $this->translator->translate(ClientViewFieldsHutkigrosh::WEBPAY_TAB_LABEL);
    }

    /**
     * @return string
     */
    public function getWebpayButtonLabel()
    {
        return $this->translator->translate(ClientViewFieldsHutkigrosh::WEBPAY_BUTTON_LABEL);
    }


    /**
     * @return string
     */
    public function getWebpayDetails()
    {
        return $this->translator->translate(ClientViewFieldsHutkigrosh::WEBPAY_DETAILS);
    }

    /**
     * @return string
     */
    public function getWebpayMsgSuccess()
    {
        return $this->translator->translate(ClientViewFieldsHutkigrosh::WEBPAY_MSG_SUCCESS);
    }

    /**
     * @return string
     */
    public function getWebpayMsgUnsuccess()
    {
        return $this->translator->translate(ClientViewFieldsHutkigrosh::WEBPAY_MSG_UNSUCCESS);
    }

    /**
     * @return string
     */
    public function getWebpayMsgUnavailable()
    {
        return $this->translator->translate(ClientViewFieldsHutkigrosh::WEBPAY_MSG_UNAVAILABLE);
    }

    /**
     * @return bool
     */
    public function isAlfaclickSectionEnabled()
    {
        return $this->configWrapper->isAlfaclickSectionEnabled();
    }

    /**
     * @return mixed
     */
    public function getAlfaclickBillID()
    {
        return $this->orderWrapper->getExtId();
    }

    /**
     * @return mixed
     */
    public function getAlfaclickPhone()
    {
        return $this->orderWrapper->getMobilePhone();
    }

    /**
     * @return mixed
     */
    public function getAlfaclickUrl()
    {
        return $this->alfaclickUrl;
    }

    /**
     * @param mixed $alfaclickUrl
     */
    public function setAlfaclickUrl($alfaclickUrl)
    {
        $this->alfaclickUrl = $alfaclickUrl;
    }

    /**
     * @return string
     */
    public function getAlfaclickTabLabel()
    {
        return $this->translator->translate(ClientViewFieldsHutkigrosh::ALFACLICK_TAB_LABEL);
    }

    /**
     * @return string
     */
    public function getAlfaclickButtonLabel()
    {
        return $this->translator->translate(ClientViewFieldsHutkigrosh::ALFACLICK_BUTTON_LABEL);
    }

    /**
     * @return string
     */
    public function getAlfaclickDetails()
    {
        return $this->translator->translate(ClientViewFieldsHutkigrosh::ALFACLICK_DETAILS);
    }

    /**
     * @return string
     */
    public function getAlfaclickMsgSuccess()
    {
        return $this->translator->translate(ClientViewFieldsHutkigrosh::ALFACLICK_MSG_SUCCESS);
    }

    /**
     * @return string
     */
    public function getAlfaclickMsgUnsuccess()
    {
        return $this->translator->translate(ClientViewFieldsHutkigrosh::ALFACLICK_MSG_UNSUCCESS);
    }


    public function elementTab($key, $header, $body, $selectable = true)
    {
        return
            element::div(
                attribute::id("tab-" . $key),
                attribute::clazz("tab " . $this->getCssClass4Tab()),
                $this->elementTabHeaderInput($key, $selectable),
                $this->elementTabHeader($key, $header),
                $this->elementTabBody($key, $body)
            )->__toString();
    }

    public function elementTabHeader($key, $header)
    {
        return
            element::div(
                attribute::clazz("tab-header " . $this->getCssClass4TabHeader()),
                element::label(
                    attribute::forr("input-" . $key),
                    attribute::clazz($this->getCssClass4TabHeaderLabel()),
                    element::content($header)
                )
            );
    }

    public function elementTabHeaderInput($key, $selectable)
    {
        return
            ($selectable ? element::input(
                attribute::id("input-" . $key),
                attribute::type("radio"),
                attribute::name("tabs2"),
                attribute::checked($this->isTabChecked($key))
            ) : "");
    }

    public function elementTabBody($key, $body)
    {
        return
            element::div(
                attribute::clazz("tab-body " . $this->getCssClass4TabBody()),
                element::div(
                    attribute::id($key . "-content"),
                    attribute::clazz("tab-body-content " . $this->getCssClass4TabBodyContent()),
                    element::content($body)
                )
            );
    }

    public function isTabChecked($tabKey)
    {
        if ($this->isOnlyOneTabEnabled())
            return true;
        $webpayStatusPresent = '' != $this->getWebpayStatus();
        switch ($tabKey) {
            case self::TAB_KEY_INSTRUCTIONS:
                return !$webpayStatusPresent;
            case self::TAB_KEY_WEBPAY:
                return $webpayStatusPresent;
            default:
                return false;
        }
    }

    public function isOnlyOneTabEnabled()
    {
        $enabledTabsCount = 0;
        if ($this->configWrapper->isInstructionsSectionEnabled())
            $enabledTabsCount++;
        if ($this->configWrapper->isQRCodeSectionEnabled())
            $enabledTabsCount++;
        if ($this->configWrapper->isWebpaySectionEnabled())
            $enabledTabsCount++;
        if ($this->configWrapper->isAlfaclickSectionEnabled())
            $enabledTabsCount++;
        return $enabledTabsCount == 1;
    }

    const TAB_KEY_WEBPAY = "webpay";
    const TAB_KEY_INSTRUCTIONS = "instructions";
    const TAB_KEY_QRCODE = "qrcode";
    const TAB_KEY_ALFACLICK = "alfaclick";

    public function elementWebpayTab()
    {
        if ($this->isWebpaySectionEnabled()) {
            return $this->elementTab(
                self::TAB_KEY_WEBPAY,
                $this->getWebpayTabLabel(),
                $this->elementWebpayTabContent($this->getWebpayStatus(), $this->getWebpayForm()));
        }
        return "";
    }

    public function elementInstructionsTab()
    {
        if ($this->isInstructionsSectionEnabled()) {
            return $this->elementTab(
                self::TAB_KEY_INSTRUCTIONS,
                $this->getInstructionsTabLabel(),
                $this->getInstructionsText());
        }
        return "";
    }

    public function elementQRCodeTab()
    {
        if ($this->isQRCodeSectionEnabled()) {
            return $this->elementTab(
                self::TAB_KEY_QRCODE,
                $this->getQRCodeTabLabel(),
                $this->getQRCodeDetails());
        }
        return "";
    }

    public function elementAlfaclickTab()
    {
        if ($this->isAlfaclickSectionEnabled()) {
            return $this->elementTab(
                self::TAB_KEY_ALFACLICK,
                $this->getAlfaclickTabLabel(),
                $this->elementAlfaclickTabContent());
        }
        return "";
    }

    public function getCoreCSSFilePath()
    {
        return dirname(__FILE__) . "/accordion.css";
    }

    public function getModuleCSSFilePath()
    {
        return "";
    }

    public function getAdditionalCSSFilePath()
    {
        if ("default" == $this->configWrapper->getCompletionCssFile())
            return dirname(__FILE__) . "/completion-default.css";
        else
            return $_SERVER['DOCUMENT_ROOT'] . $this->configWrapper->getCompletionCssFile();
    }

    const STATUS_PAYED = 'payed';
    const STATUS_FAILED = 'failed';

    public function elementWebpayTabContent($status, $webpayForm)
    {
        $ret =
            element::div(
                attribute::id("webpay_details"),
                element::content($this->translator->translate(ClientViewFieldsHutkigrosh::WEBPAY_DETAILS)),
                element::br());

        $ret .= $this->elementWebpayTabContentResultMsg($status);

        if ("" != $webpayForm) {
            $ret .=
                element::div(
                    attribute::id("webpay"),
                    attribute::align("right"),
                    element::img(
                        attribute::id("webpay-ps-image"),
                        attribute::src(ResourceUtils::getResourceUrl(dirname(dirname(dirname(__FILE__))) . '/image/ps_icons.png')),
                        attribute::alt("")
                    ),
                    element::br(),
                    element::content($webpayForm),
                    element::includeFile(dirname(__FILE__) . "/webpayJs.php", ["completionPanel" => $this]));
        } else {
            $ret .=
                element::div(
                    attribute::id("webpay_message_unavailable"),
                    attribute::clazz($this->getCssClass4MsgUnsuccess()),
                    element::content($this->translator->translate(ClientViewFieldsHutkigrosh::WEBPAY_MSG_UNAVAILABLE)));
        }
        return $ret;
    }

    public function elementWebpayTabContentResultMsg($status)
    {
        if (self::STATUS_PAYED == $status) {
            return
                element::div(
                    attribute::clazz($this->getCssClass4MsgSuccess()),
                    attribute::id("webpay_message"),
                    element::content($this->translator->translate(ClientViewFieldsHutkigrosh::WEBPAY_MSG_SUCCESS)));
        } elseif (self::STATUS_FAILED == $status) {
            return
                element::div(
                    attribute::clazz($this->getCssClass4MsgUnsuccess()),
                    attribute::id("webpay_message"),
                    element::content($this->translator->translate(ClientViewFieldsHutkigrosh::WEBPAY_MSG_UNSUCCESS)));
        } else
            return "";
    }

    const ALFACLICK_URL = "alfaclickurl";

    public function elementAlfaclickTabContent()
    {
        $content =
            element::content(
                element::div(
                    attribute::id("alfaclick_details"),
                    element::content($this->getAlfaclickDetails()),
                    element::br()),
                $this->elementAlfaclickTabContentForm(),
                element::includeFile(dirname(__FILE__) . "/alfaclickJs.php", ["completionPanel" => $this]));

        return $content;
    }

    public function elementAlfaclickTabContentForm()
    {
        return
            element::div(
                attribute::id("alfaclick_form"),
                attribute::clazz($this->getCssClass4AlfaclickForm()),
                attribute::align("right"),
                element::input(
                    attribute::id("billID"),
                    attribute::type('hidden'),
                    attribute::value($this->getAlfaclickBillID())),
                element::input(
                    attribute::id("phone"),
                    attribute::type('tel'),
                    attribute::value($this->getAlfaclickPhone()),
                    attribute::clazz($this->getCssClass4FormInput()),
                    attribute::maxlength('20')),
                element::a(
                    attribute::clazz("hutkigrosh-button " . $this->getCssClass4AlfaclickButton()),
                    attribute::id("alfaclick_button"),
                    element::content($this->getAlfaclickButtonLabel()))
            );
    }


    /**
     * @return string
     */
    public function getCssClass4Tab()
    {
        return "";
    }

    /**
     * @return string
     */
    public function getCssClass4TabHeader()
    {
        return "";
    }

    /**
     * @return string
     */
    public function getCssClass4TabHeaderLabel()
    {
        return "";
    }

    /**
     * @return string
     */
    public function getCssClass4TabBody()
    {
        return "";
    }

    /**
     * @return string
     */
    public function getCssClass4TabBodyContent()
    {
        return "";
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
    public function getCssClass4MsgUnsuccess()
    {
        return "";
    }

    /**
     * @return string
     */
    public function getCssClass4CompletionTextDiv()
    {
        return "";
    }

    /**
     * @return string
     */
    public function getCssClass4TabsGroup()
    {
        return "";
    }


    /**
     * @return string
     */
    public function getCssClass4AlfaclickButton()
    {
        return $this->getCssClass4Button();
    }

    /**
     * @return string
     */
    public function getCssClass4WebpayButton()
    {
        return $this->getCssClass4Button();
    }

    /**
     * @return string
     */
    public function getCssClass4Button()
    {
        return "";
    }

    public function getCssClass4AlfaclickForm()
    {
        return "";
    }

    public function getCssClass4FormInput()
    {
        return "";
    }
}