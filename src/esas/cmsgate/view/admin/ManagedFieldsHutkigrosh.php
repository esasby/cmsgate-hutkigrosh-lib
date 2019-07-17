<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 30.09.2018
 * Time: 15:15
 */

namespace esas\cmsgate\view\admin;


use esas\cmsgate\ConfigFieldsHutkigrosh;
use esas\cmsgate\utils\Logger;
use esas\cmsgate\view\admin\fields\ConfigField;
use esas\cmsgate\view\admin\fields\ConfigFieldCheckbox;
use esas\cmsgate\view\admin\fields\ConfigFieldList;
use esas\cmsgate\view\admin\fields\ConfigFieldNumber;
use esas\cmsgate\view\admin\fields\ConfigFieldPassword;
use esas\cmsgate\view\admin\fields\ConfigFieldRichtext;
use esas\cmsgate\view\admin\fields\ConfigFieldStatusList;
use esas\cmsgate\view\admin\fields\ConfigFieldText;
use esas\cmsgate\view\admin\fields\ConfigFieldTextarea;
use esas\cmsgate\view\admin\fields\ListOption;
use esas\cmsgate\view\admin\validators\ValidationResult;
use esas\cmsgate\view\admin\validators\ValidatorEmail;
use esas\cmsgate\view\admin\validators\ValidatorImpl;
use esas\cmsgate\view\admin\validators\ValidatorInteger;
use esas\cmsgate\view\admin\validators\ValidatorNotEmpty;
use esas\cmsgate\view\admin\validators\ValidatorNumeric;

class ManagedFieldsHutkigrosh extends ManagedFields
{
        /**
     * ManagedFieldsHutkigrosh constructor.
     */
    public function __construct()
    {
        parent::__construct();
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
            (new ConfigFieldCheckbox(ConfigFieldsHutkigrosh::notificationEmail())));
        $this->registerField(
            (new ConfigFieldCheckbox(ConfigFieldsHutkigrosh::notificationSms())));
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
        $this->registerField(new ConfigFieldStatusList(ConfigFieldsHutkigrosh::billStatusPending()));
        $this->registerField(new ConfigFieldStatusList(ConfigFieldsHutkigrosh::billStatusPayed()));
        $this->registerField(new ConfigFieldStatusList(ConfigFieldsHutkigrosh::billStatusFailed()));
        $this->registerField(new ConfigFieldStatusList(ConfigFieldsHutkigrosh::billStatusCanceled()));
        $this->registerField(
            (new ConfigFieldCheckbox(ConfigFieldsHutkigrosh::instructionsSection())));
        $this->registerField(
            (new ConfigFieldCheckbox(ConfigFieldsHutkigrosh::qrcodeSection())));
        $this->registerField(
            (new ConfigFieldCheckbox(ConfigFieldsHutkigrosh::alfaclickSection())));
        $this->registerField(
            (new ConfigFieldCheckbox(ConfigFieldsHutkigrosh::webpaySection())));
        $this->registerField(
            (new ConfigFieldRichtext(ConfigFieldsHutkigrosh::completionText()))
                ->setRequired(true));
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
                ->setRequired(true)
                ->setValidator(new ValidatorNotEmpty()));

    }
}



