<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 16.02.2018
 * Time: 13:39
 */

namespace esas\cmsgate\hutkigrosh\wrappers;

use esas\cmsgate\hutkigrosh\ConfigFieldsHutkigrosh;
use esas\cmsgate\Registry;
use esas\cmsgate\wrappers\ConfigWrapper;
use esas\cmsgate\wrappers\OrderWrapper;

class ConfigWrapperHutkigrosh extends ConfigWrapper
{
    /**
     * Произольно название интернет-мазагина
     * @return string
     */
    public function getShopName()
    {
        return $this->getConfig(ConfigFieldsHutkigrosh::shopName());
    }

    /**
     * Имя пользователя для доступа к системе ХуткиГрош
     * @return string
     */
    public function getHutkigroshLogin()
    {
        return $this->getConfig(ConfigFieldsHutkigrosh::login());
    }

    /**
     * Пароль для доступа к системе ХуткиГрош
     * @return string
     */
    public function getHutkigroshPassword()
    {
        return $this->getConfig(ConfigFieldsHutkigrosh::password());
    }


    /**
     * Необходимо ли добавлять кнопку "Инструкиция по оплате в ЕРИП"
     * @return boolean
     */
    public function isInstructionsSectionEnabled()
    {
        return $this->checkOn(ConfigFieldsHutkigrosh::instructionsSection());
    }

    /**
     * Необходимо ли добавлять кнопку "выставить в Alfaclick"
     * @return boolean
     */
    public function isAlfaclickSectionEnabled()
    {
        return $this->checkOn(ConfigFieldsHutkigrosh::alfaclickSection());
    }

    /**
     * Необходимо ли добавлять кнопку "оплатить картой"
     * @return boolean
     */
    public function isWebpaySectionEnabled()
    {
        return $this->checkOn(ConfigFieldsHutkigrosh::webpaySection());
    }

    /**
     * Уникальный идентификатор услуги в ЕРИП
     * @return string
     */
    public function getEripId()
    {
        return $this->getConfig(ConfigFieldsHutkigrosh::eripId());
    }

    /**
     * Номер услуги в дереве ЕРИП
     * @return string
     */
    public function getEripTreeId()
    {
        return $this->getConfig(ConfigFieldsHutkigrosh::eripTreeId());
    }

    /**
     * Необходимо ли добавлять секцию с QR-code
     * @return boolean
     */
    public function isQRCodeSectionEnabled()
    {
        return $this->checkOn(ConfigFieldsHutkigrosh::qrcodeSection());
    }

    /**
     * Включена ля оповещение клиента по Email
     * @return boolean
     */
    public function isEmailNotification()
    {
        return $this->checkOn(ConfigFieldsHutkigrosh::notificationEmail());
    }

    /**
     * Включена ля оповещение клиента по Sms
     * @return boolean
     */
    public function isSmsNotification()
    {
        return $this->checkOn(ConfigFieldsHutkigrosh::notificationSms());
    }

    /**
     * Итоговый текст, отображаемый клиенту после успешного выставления счета
     * @return string
     */
    public function getCompletionText()
    {
        return $this->getConfigOrDefaults(ConfigFieldsHutkigrosh::completionText());
    }

    /***
     * CSS для итогового экрана. Необходим для урощение возможности кастомизации под тему магазина
     * @return string
     */
    public function getCompletionCssFile()
    {
        return $this->getConfig(ConfigFieldsHutkigrosh::completionCssFile());
    }

    /***
     * В некоторых CMS не получается в настройках хранить html, поэтому использует текст итогового экрана по умолчанию,
     * в который проставлятся значение ERIPPATh
     * @return string
     */
    public function getEripPath()
    {
        return $this->getConfig(ConfigFieldsHutkigrosh::eripPath());
    }

    /**
     * Кастомный путь к директории cookie файлов
     * @return string
     */
    public function getCookiePath()
    {
        return $this->getConfig(ConfigFieldsHutkigrosh::cookiePath());
    }

    /**
     * Какой срок действия счета после его выставления (в днях)
     * @return string
     */
    public function getDueInterval()
    {
        return $this->getConfig(ConfigFieldsHutkigrosh::dueInterval());
    }


    /**
     * Метод для получения значения праметра по ключу
     * @param $config_key
     * @return bool|string
     */
    public function get($config_key)
    {
        switch ($config_key) {
            // сперва пробегаем по соответствующим методам, на случай если они были переопределены в дочернем классе
            case ConfigFieldsHutkigrosh::shopName():
                return $this->getShopName();
            case ConfigFieldsHutkigrosh::login():
                return $this->getHutkigroshLogin();
            case ConfigFieldsHutkigrosh::password():
                return $this->getHutkigroshPassword();
            case ConfigFieldsHutkigrosh::eripId():
                return $this->getEripId();
            case ConfigFieldsHutkigrosh::eripTreeId():
                return $this->getEripTreeId();
            case ConfigFieldsHutkigrosh::instructionsSection():
                return $this->isInstructionsSectionEnabled();
            case ConfigFieldsHutkigrosh::qrcodeSection():
                return $this->isQRCodeSectionEnabled();
            case ConfigFieldsHutkigrosh::alfaclickSection():
                return $this->isAlfaclickSectionEnabled();
            case ConfigFieldsHutkigrosh::webpaySection():
                return $this->isWebpaySectionEnabled();
            case ConfigFieldsHutkigrosh::notificationEmail():
                return $this->isEmailNotification();
            case ConfigFieldsHutkigrosh::notificationSms():
                return $this->isSmsNotification();
            case ConfigFieldsHutkigrosh::completionText():
                return $this->getCompletionText();
            case ConfigFieldsHutkigrosh::completionCssFile():
                return $this->getCompletionCssFile();
            case ConfigFieldsHutkigrosh::dueInterval():
                return $this->getDueInterval();
            case ConfigFieldsHutkigrosh::eripPath():
                return $this->getEripPath();
            case ConfigFieldsHutkigrosh::cookiePath():
                return $this->getCookiePath();
            default:
                return parent::get($config_key);
        }
    }

    /**
     * Производит подстановку переменных из заказа в итоговый текст
     * @param $text
     * @param OrderWrapper $orderWrapper
     * @return string
     */
    public function cookText($text, $orderWrapper)
    {
        $text = parent::cookText($text, $orderWrapper);
        return strtr($text, array(
            "@erip_path" => $this->getEripPath()
        ));
    }

    /**
     * Нельзя делать в конструкторе
     * @param $key
     * @return bool|int|string
     */
    public function getDefaultConfig($key)
    {
        switch ($key) {
            case ConfigFieldsHutkigrosh::sandbox():
                return true;
            case ConfigFieldsHutkigrosh::notificationEmail():
                return true;
            case ConfigFieldsHutkigrosh::notificationSms():
                return true;
            case ConfigFieldsHutkigrosh::dueInterval():
                return 2;
            case ConfigFieldsHutkigrosh::instructionsSection():
                return true;
            case ConfigFieldsHutkigrosh::qrcodeSection():
                return true;
            case ConfigFieldsHutkigrosh::alfaclickSection():
                return false;
            case ConfigFieldsHutkigrosh::webpaySection():
                return true;
            default:
                return Registry::getRegistry()->getTranslator()->getConfigFieldDefault($key);
        }
    }

}