<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 05.05.2020
 * Time: 15:19
 */

namespace esas\cmsgate\hutkigrosh\view\admin;


use esas\cmsgate\hutkigrosh\ConfigFieldsHutkigrosh;
use esas\cmsgate\view\admin\fields\ConfigFieldCheckbox;
use esas\cmsgate\view\admin\fields\ConfigFieldNumber;
use esas\cmsgate\view\admin\fields\ConfigFieldPassword;
use esas\cmsgate\view\admin\fields\ConfigFieldRichtext;
use esas\cmsgate\view\admin\fields\ConfigFieldStatusList;
use esas\cmsgate\view\admin\fields\ConfigFieldText;
use esas\cmsgate\view\admin\ManagedFieldsFactory;
use esas\cmsgate\view\admin\validators\ValidatorEmail;
use esas\cmsgate\view\admin\validators\ValidatorImpl;
use esas\cmsgate\view\admin\validators\ValidatorInteger;
use esas\cmsgate\view\admin\validators\ValidatorNotEmpty;
use esas\cmsgate\view\admin\validators\ValidatorNumeric;

class ManagedFieldsFactoryHutkigrosh extends ManagedFieldsFactory
{
    public function initFields()
    {
        $this->registerField(
            (new ConfigFieldText(ConfigFieldsHutkigrosh::shopName()))
                ->setValidator(new ValidatorNotEmpty())
                ->setRequired(false));
        $this->registerField(
            (new ConfigFieldText(ConfigFieldsHutkigrosh::login()))
                ->setValidator(new ValidatorEmail())
                ->setRequired(true));
        $this->registerField(
            (new ConfigFieldPassword(ConfigFieldsHutkigrosh::password()))
                ->setValidator(new ValidatorNotEmpty())
                ->setRequired(true));
        $this->registerField(
            (new ConfigFieldNumber(ConfigFieldsHutkigrosh::eripId()))
                ->setValidator(new ValidatorNumeric())
                ->setRequired(true));
        $this->registerField(
            (new ConfigFieldNumber(ConfigFieldsHutkigrosh::eripTreeId()))
                ->setValidator(new ValidatorNumeric())
                ->setRequired(true));
        $this->registerField(
            (new ConfigFieldCheckbox(ConfigFieldsHutkigrosh::sandbox())));
        $this->registerField(
            (new ConfigFieldCheckbox(ConfigFieldsHutkigrosh::debugMode())));
        $this->registerField(
            (new ConfigFieldCheckbox(ConfigFieldsHutkigrosh::notificationEmail())));
        $this->registerField(
            (new ConfigFieldCheckbox(ConfigFieldsHutkigrosh::notificationSms())));
        $this->registerField(
            (new ConfigFieldCheckbox(ConfigFieldsHutkigrosh::useOrderNumber())));
        $this->registerField(
            (new ConfigFieldText(ConfigFieldsHutkigrosh::eripPath()))
                ->setRequired(true)
                ->setValidator(new ValidatorNotEmpty()));
        $this->registerField(
            (new ConfigFieldNumber(ConfigFieldsHutkigrosh::dueInterval()))
                ->setMin(1)
                ->setMax(10)
                ->setValidator(new ValidatorInteger(1, 10))
                ->setRequired(true));
        $this->registerField(
            (new ConfigFieldStatusList(ConfigFieldsHutkigrosh::orderStatusPending()))
                ->setRequired(true));
        $this->registerField(
            (new ConfigFieldStatusList(ConfigFieldsHutkigrosh::orderStatusPayed()))
                ->setRequired(true));
        $this->registerField(
            (new ConfigFieldStatusList(ConfigFieldsHutkigrosh::orderStatusFailed()))
                ->setRequired(true));
        $this->registerField(
            (new ConfigFieldStatusList(ConfigFieldsHutkigrosh::orderStatusCanceled()))
                ->setRequired(true));
        $this->registerField(
            (new ConfigFieldCheckbox(ConfigFieldsHutkigrosh::instructionsSection())));
        $this->registerField(
            (new ConfigFieldCheckbox(ConfigFieldsHutkigrosh::qrcodeSection())));
        $this->registerField(
            (new ConfigFieldCheckbox(ConfigFieldsHutkigrosh::alfaclickSection())));
        $this->registerField(
            (new ConfigFieldCheckbox(ConfigFieldsHutkigrosh::webpaySection())));
        $this->registerField(
            (new ConfigFieldRichtext(ConfigFieldsHutkigrosh::completionText())));
        $this->registerField(
            (new ConfigFieldText(ConfigFieldsHutkigrosh::completionCssFile()))
                ->setRequired(false)
                ->setValidator(new ValidatorImpl()));
        $this->registerField(
            (new ConfigFieldText(ConfigFieldsHutkigrosh::paymentMethodName()))
                ->setRequired(true)
                ->setValidator(new ValidatorNotEmpty()));
        $this->registerField(
            (new ConfigFieldRichtext(ConfigFieldsHutkigrosh::paymentMethodDetails()))
                ->setRequired(false)
                ->setValidator(new ValidatorNotEmpty()));
        $this->registerField(
            (new ConfigFieldText(ConfigFieldsHutkigrosh::paymentMethodNameWebpay()))
                ->setRequired(true)
                ->setValidator(new ValidatorNotEmpty()));
        $this->registerField(
            (new ConfigFieldRichtext(ConfigFieldsHutkigrosh::paymentMethodDetailsWebpay()))
                ->setRequired(true)
                ->setValidator(new ValidatorNotEmpty()));
    }
}