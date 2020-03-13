<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 10.08.2018
 * Time: 12:21
 */

namespace esas\cmsgate\hutkigrosh;


use esas\cmsgate\ConfigFields;

class ConfigFieldsHutkigrosh extends ConfigFields
{
    public static function shopName()
    {
        return self::getCmsRelatedKey("shop_name");
    }

    public static function login()
    {
        return self::getCmsRelatedKey("hg_login");
    }

    public static function password()
    {
        return self::getCmsRelatedKey("hg_password");
    }

    public static function eripId()
    {
        return self::getCmsRelatedKey("erip_id");
    }

    public static function eripTreeId()
    {
        return self::getCmsRelatedKey("erip_tree_id");
    }

    public static function instructionsSection()
    {
        return self::getCmsRelatedKey("instructions_section");
    }

    public static function qrcodeSection()
    {
        return self::getCmsRelatedKey("qrcode_section");
    }

    public static function webpaySection()
    {
        return self::getCmsRelatedKey("webpay_section");
    }

    public static function alfaclickSection()
    {
        return self::getCmsRelatedKey("alfaclick_section");
    }

    public static function notificationEmail()
    {
        return self::getCmsRelatedKey("notification_email");
    }

    public static function notificationSms()
    {
        return self::getCmsRelatedKey("notification_sms");
    }

    public static function completionText()
    {
        return self::getCmsRelatedKey("completion_text");
    }

    public static function completionCssFile()
    {
        return self::getCmsRelatedKey("completion_css_file");
    }

    public static function eripPath()
    {
        return self::getCmsRelatedKey("erip_path");
    }

    public static function dueInterval()
    {
        return self::getCmsRelatedKey("due_interval");
    }
}