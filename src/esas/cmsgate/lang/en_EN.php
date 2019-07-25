<?php

use esas\cmsgate\ConfigFieldsHutkigrosh;
use esas\cmsgate\view\client\ViewFields;

return array(
    ConfigFieldsHutkigrosh::shopName() => 'Shop name',
    ConfigFieldsHutkigrosh::shopName() . _DESC => 'Your shop short name',

    ConfigFieldsHutkigrosh::login() => 'Login',
    ConfigFieldsHutkigrosh::login() . _DESC => 'Hutkigrosh gateway login',

    ConfigFieldsHutkigrosh::password() => 'Password',
    ConfigFieldsHutkigrosh::password() . _DESC => 'Hutkigrosh gateway password',

    ConfigFieldsHutkigrosh::eripId() => 'ERIP ID',
    ConfigFieldsHutkigrosh::eripId() . _DESC => 'Your shop ERIP unique id',

    ConfigFieldsHutkigrosh::eripTreeId() => 'ERIP Tree code',
    ConfigFieldsHutkigrosh::eripTreeId() . _DESC => 'ERIP Tree code',

    ConfigFieldsHutkigrosh::sandbox() => 'Sandbox',
    ConfigFieldsHutkigrosh::sandbox() . _DESC => 'Sandbox mode. If *true* then all requests will be sent to trial host trial.hgrosh.by',

    ConfigFieldsHutkigrosh::instructionsSection() => 'Section Instructions',
    ConfigFieldsHutkigrosh::instructionsSection() . _DESC => 'If *true* then customer will see step-by-step instructions to pay bill with ERIP',

    ConfigFieldsHutkigrosh::qrcodeSection() => 'Section QR-code',
    ConfigFieldsHutkigrosh::qrcodeSection() . _DESC => 'If *true* then customer will be able to pay bill with QR-code',

    ConfigFieldsHutkigrosh::alfaclickSection() => 'Section Alfaclick',
    ConfigFieldsHutkigrosh::alfaclickSection() . _DESC => 'If *true* then customer will get *Add to Alfaclick* button on success page',

    ConfigFieldsHutkigrosh::webpaySection() => 'Section Webpay',
    ConfigFieldsHutkigrosh::webpaySection() . _DESC => 'If *true* then customer will get *Pay with car* button on success page',

    ConfigFieldsHutkigrosh::notificationEmail() => 'Email notification',
    ConfigFieldsHutkigrosh::notificationEmail() . _DESC => 'If *true* then Hutkigrosh gateway will sent email notification to customer',

    ConfigFieldsHutkigrosh::notificationSms() => 'Sms notification',
    ConfigFieldsHutkigrosh::notificationSms() . _DESC => 'If *true* then Hutkigrosh gateway will sent sms notification to customer',

    ConfigFieldsHutkigrosh::completionText() => 'Completion text',
    ConfigFieldsHutkigrosh::completionText() . _DESC => 'Text displayed to the client after the successful invoice. Can contain html. ' .
        'In the text you can refer to variables @order_id, @order_number, @order_total, @order_currency, @order_fullname, @order_phone, @order_address',
    ConfigFieldsHutkigrosh::completionText() . _DEFAULT => '<p>Bill #<strong>@order_number</strong> was successfully placed in ERIP</p>
<p>You can pay it in cash, a plastic card and electronic money, in any bank, cash departments, ATMs, payment terminals, in the system of electronic money, through Internet banking, M-banking, online acquiring</p>',

    ConfigFieldsHutkigrosh::completionCssFile() => 'Completion page CSS file',
    ConfigFieldsHutkigrosh::completionCssFile() . _DESC => 'Optional CSS file path for completion page',

    ConfigFieldsHutkigrosh::paymentMethodName() => 'Payment method name',
    ConfigFieldsHutkigrosh::paymentMethodName() . _DESC => 'Name displayed to the customer when choosing a payment method',
    ConfigFieldsHutkigrosh::paymentMethodName() . _DEFAULT => 'AIS *Raschet* (ERIP)',

    ConfigFieldsHutkigrosh::paymentMethodDetails() => 'Payment method details',
    ConfigFieldsHutkigrosh::paymentMethodDetails() . _DESC => 'Description of the payment method that will be shown to the client at the time of payment',
    ConfigFieldsHutkigrosh::paymentMethodDetails() . _DEFAULT => 'Hutkigrosh™ — payment service for invoicing in AIS *Raschet* (ERIP). After invoicing you will be available for payment by a plastic card and electronic money, at any of the bank branches, cash desks, ATMs, payment terminals, in the electronic money system, through Internet banking, M-banking, Internet acquiring',

    ConfigFieldsHutkigrosh::billStatusPending() => 'Bill status pending',
    ConfigFieldsHutkigrosh::billStatusPending() . _DESC => 'Mapped status for pending bills',

    ConfigFieldsHutkigrosh::billStatusPayed() => 'Bill status payed',
    ConfigFieldsHutkigrosh::billStatusPayed() . _DESC => 'Mapped status for payed bills',

    ConfigFieldsHutkigrosh::billStatusFailed() => 'Bill status failed',
    ConfigFieldsHutkigrosh::billStatusFailed() . _DESC => 'Mapped status for failed bills',

    ConfigFieldsHutkigrosh::billStatusCanceled() => 'Bill status canceled',
    ConfigFieldsHutkigrosh::billStatusCanceled() . _DESC => 'Mapped status for canceled bills',

    ConfigFieldsHutkigrosh::dueInterval() => 'Bill due interval (days)',
    ConfigFieldsHutkigrosh::dueInterval() . _DESC => 'How many days new bill will be available for payment',

    ConfigFieldsHutkigrosh::eripPath() => 'ERIP PATH',
    ConfigFieldsHutkigrosh::eripPath() . _DESC => 'По какому пути клиент должен искать выставленный счет',

    ViewFields::INSTRUCTIONS_TAB_LABEL => 'Payment instructions',
    ViewFields::INSTRUCTIONS => '<p>To pay an bill in ERIP:</p>
<ol>
    <li>Select the ERIP payment tree</li>
    <li>Select a service: <strong>@erip_path</strong></li>
    <li>Enter bill number <strong>@order_number</strong></li>
    <li>Verify information is correct</li>
    <li>Make a payment</li>
</ol>',

    ViewFields::QRCODE_TAB_LABEL => 'Pay with QR-code',
    ViewFields::QRCODE_DETAILS => '<p>You can pay this bill by QR-code:</p>
<div align="center">@qr_code</div>
<p>To get information about mobile apps with QR-code payment support please visit <a href="http://pay.raschet.by/" target="_blank"style="color: #8c2003;"><span>this link</span></a></p>',


    ViewFields::ALFACLICK_TAB_LABEL => 'Add bill to «Alfa-click»',
    ViewFields::ALFACLICK_DETAILS => 'You can add bill to «Alfa-click» system (e-Invoicing)',
    ViewFields::ALFACLICK_BUTTON_LABEL => 'Add bill',
    ViewFields::ALFACLICK_MSG_SUCCESS => 'Bill was added to «Alfa-click»',
    ViewFields::ALFACLICK_MSG_UNSUCCESS => 'Can not add bill to «Alfa-click»',

    ViewFields::WEBPAY_TAB_LABEL => 'Pay with card',
    ViewFields::WEBPAY_DETAILS => 'You can pay bill with Visa, Mastercard or Belcard',
    ViewFields::WEBPAY_BUTTON_LABEL => 'Continue',
    ViewFields::WEBPAY_MSG_SUCCESS => 'Webpay: payment completed!',
    ViewFields::WEBPAY_MSG_UNSUCCESS => 'Webpay: payment failed!',
    ViewFields::WEBPAY_MSG_UNAVAILABLE => 'Sorry, operation currently not available',
);