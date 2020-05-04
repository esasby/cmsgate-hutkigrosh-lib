<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 14.04.2020
 * Time: 13:45
 */

namespace esas\cmsgate\hutkigrosh;


use esas\cmsgate\hutkigrosh\lang\TranslatorHutkigrosh;
use esas\cmsgate\hutkigrosh\wrappers\ConfigWrapperHutkigrosh;
use esas\cmsgate\PaysystemConnector;

class PaysystemConnectorHutkigrosh extends PaysystemConnector
{

    public function createConfigWrapper()
    {
        return new ConfigWrapperHutkigrosh();
    }

    public function createTranslator()
    {
        return new TranslatorHutkigrosh();
    }
}