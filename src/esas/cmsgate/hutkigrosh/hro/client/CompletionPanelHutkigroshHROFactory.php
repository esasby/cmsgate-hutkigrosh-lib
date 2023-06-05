<?php


namespace esas\cmsgate\hutkigrosh\hro\client;


use esas\cmsgate\hro\HROFactory;
use esas\cmsgate\hro\HROManager;

class CompletionPanelHutkigroshHROFactory implements HROFactory
{
    /**
     * @return CompletionPanelHutkigroshHRO
     */
    public static function findBuilder() {
        return HROManager::fromRegistry()->getImplementation(CompletionPanelHutkigroshHRO::class, CompletionPanelHutkigroshHRO_v1::class);
    }
}