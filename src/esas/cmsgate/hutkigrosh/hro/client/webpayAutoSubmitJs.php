<?php
/** @var array $scriptData */
/** @var \esas\cmsgate\hutkigrosh\view\client\CompletionPanelHutkigrosh $completionPanel */
$completionPanel = $this->scriptData["completionPanel"];
?>

<script type="text/javascript">
    var webpay_form = $('#webpay form');
    webpay_form.submit();
</script>