<?php


namespace esas\cmsgate\hutkigrosh\utils;


use esas\cmsgate\utils\ResourceUtils;

class ResourceUtilsHutkigrosh extends ResourceUtils
{
    private static function getImageDir() {
        return dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "/static/image/";
    }

    public static function getPsImageUrl() {
        return self::getImageUrl(self::getImageDir(), 'ps_icons.png');
    }

    public static function getLogoHutkigroshWhite() {
        return self::getImageUrl(self::getImageDir(), 'hutkigrosh_by_white.svg');
    }

    public static function getLogoHutkigroshWhiteVertical() {
        return self::getImageUrl(self::getImageDir(), 'hutkigrosh_by_white_vertical.svg');
    }
}