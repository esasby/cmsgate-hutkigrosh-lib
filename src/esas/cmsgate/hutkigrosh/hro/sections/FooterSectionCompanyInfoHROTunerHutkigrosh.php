<?php


namespace esas\cmsgate\hutkigrosh\hro\sections;


use esas\cmsgate\hro\HRO;
use esas\cmsgate\hro\HROTuner;
use esas\cmsgate\hro\sections\FooterSectionCompanyInfoHRO;
use esas\cmsgate\hutkigrosh\view\client\ClientViewFieldsHutkigrosh;
use esas\cmsgate\lang\Translator;

class FooterSectionCompanyInfoHROTunerHutkigrosh implements HROTuner
{
    /**
     * @param FooterSectionCompanyInfoHRO $hroBuilder
     * @return HRO|void
     */
    public function tune($hroBuilder) {
        return $hroBuilder
            ->addAboutItem(Translator::fromRegistry()->translate(ClientViewFieldsHutkigrosh::HUTKIGROSH_ABOUT_FULL_NAME))
            ->addAboutItem(Translator::fromRegistry()->translate(ClientViewFieldsHutkigrosh::HUTKIGROSH_ABOUT_REGISTRATION_DATA))
            ->addAddressItem(Translator::fromRegistry()->translate(ClientViewFieldsHutkigrosh::HUTKIGROSH_ADDRESS_POST))
            ->addAddressItem(Translator::fromRegistry()->translate(ClientViewFieldsHutkigrosh::HUTKIGROSH_ADDRESS_LEGAL))
            ->addContactItemEmail("support@hutkigrosh.by")
            ->addContactItemPhone("+375 17 3973355")
            ->addContactItemPhone("+375 29 6353355");
    }
}