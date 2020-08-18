<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 04.06.2019
 * Time: 12:43
 */

/** @var array $scriptData */
/** @var \esas\cmsgate\hutkigrosh\view\client\CompletionPanelHutkigrosh $completionPanel */
$completionPanel = $this->scriptData["completionPanel"];
?>

<script type="text/javascript"
        src="https://ajax.aspnetcdn.com/ajax/jquery/jquery-1.11.0.min.js"></script>
<script>
    var webpay_form_button = $('#webpay input[type="submit"]');
    webpay_form_button.attr('id', 'webpay_button');
    webpay_form_button.addClass('hutkigrosh-button <?= $completionPanel->getCssClass4WebpayButton() ?>');
</script>