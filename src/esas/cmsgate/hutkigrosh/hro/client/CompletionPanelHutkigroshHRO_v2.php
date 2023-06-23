<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 24.06.2019
 * Time: 14:11
 */

namespace esas\cmsgate\hutkigrosh\hro\client;

use esas\cmsgate\utils\htmlbuilder\Attributes as attribute;
use esas\cmsgate\utils\htmlbuilder\Elements as element;

class CompletionPanelHutkigroshHRO_v2 extends CompletionPanelHutkigroshHRO_v1
{
    public static function builder() {
        return new CompletionPanelHutkigroshHRO_v2();
    }

    public function getCssClass4MsgSuccess()
    {
        return "alert alert-info";
    }

    public function getCssClass4MsgUnsuccess()
    {
        return "alert alert-danger";
    }

    public function getCssClass4Button()
    {
        return "btn btn-success";
    }

    public function getCssClass4FormInput()
    {
        return "form-control";
    }
}