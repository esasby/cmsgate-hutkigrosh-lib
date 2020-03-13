<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 16.07.2019
 * Time: 11:44
 */

namespace esas\cmsgate\hutkigrosh\lang;


use esas\cmsgate\lang\TranslatorImpl;

class TranslatorHutkigrosh extends TranslatorImpl
{
    /**
     * TranslatorHutkigrosh constructor.
     * @param $localeLoader
     */
    public function __construct($localeLoader)
    {
        parent::__construct($localeLoader, __DIR__);
    }
}