<?php


namespace esas\cmsgate\hutkigrosh\hro\sections;


use esas\cmsgate\hro\HRO;
use esas\cmsgate\hro\HROTuner;
use esas\cmsgate\hro\sections\HeaderSectionLogoContactsHRO;
use esas\cmsgate\hutkigrosh\utils\ResourceUtilsHutkigrosh;

class HeaderSectionLogoContactsHROTunerHutkigrosh implements HROTuner
{
    /**
     * @param HeaderSectionLogoContactsHRO $hroBuilder
     * @return HRO|void
     */
    public function tune($hroBuilder) {
        return $hroBuilder
            ->setLogo(ResourceUtilsHutkigrosh::getLogoHutkigroshWhite())
            ->setSmallLogo(ResourceUtilsHutkigrosh::getLogoHutkigroshWhiteVertical())
            ->addContactItemEmail("support@hutkigrosh.by")
            ->addContactItemPhone("+375 29 6353355");
    }
}