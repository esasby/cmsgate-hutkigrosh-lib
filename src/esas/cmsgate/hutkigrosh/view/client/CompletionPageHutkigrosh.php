<?php


namespace esas\cmsgate\hutkigrosh\view\client;


use esas\cmsgate\Registry;
use esas\cmsgate\view\client\CompletionPage;

class CompletionPageHutkigrosh extends CompletionPage
{

    public function getAboutArray()
    {
        return [
            $this->translator->translate(ClientViewFieldsHutkigrosh::HUTKIGROSH_ABOUT_FULL_NAME),
            $this->translator->translate(ClientViewFieldsHutkigrosh::HUTKIGROSH_ABOUT_REGISTRATION_DATA),
        ];
    }

    public function getAddressArray()
    {
        return [
            $this->translator->translate(ClientViewFieldsHutkigrosh::HUTKIGROSH_ADDRESS_POST),
            $this->translator->translate(ClientViewFieldsHutkigrosh::HUTKIGROSH_ADDRESS_LEGAL),
        ];
    }

    public function getContactsArray()
    {
        return [
            "support@hutkigrosh.by",
            "+375 17 3973355",
            "+375 29 6353355"
        ];
    }
}