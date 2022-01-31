<?php

use esas\cmsgate\hutkigrosh\ConfigFieldsHutkigrosh;
use esas\cmsgate\hutkigrosh\view\client\ClientViewFieldsHutkigrosh;
use esas\cmsgate\view\admin\AdminViewFields;
use esas\cmsgate\view\ClientViewFields;

return array(
    ConfigFieldsHutkigrosh::shopName() => 'Название магазина',
    ConfigFieldsHutkigrosh::shopName() . _DESC => 'Произвольное название Вашего магазина',

    ConfigFieldsHutkigrosh::login() => 'Логин',
    ConfigFieldsHutkigrosh::login() . _DESC => 'Ваш логин для доступа к ХуткiГрош',

    ConfigFieldsHutkigrosh::password() => 'Пароль',
    ConfigFieldsHutkigrosh::password() . _DESC => 'Ваш пароль для доступа к ХуткiГрош',

    ConfigFieldsHutkigrosh::eripId() => 'ЕРИП ID',
    ConfigFieldsHutkigrosh::eripId() . _DESC => 'Уникальный идентификатор ЕРИП',

    ConfigFieldsHutkigrosh::eripTreeId() => 'Код услуги',
    ConfigFieldsHutkigrosh::eripTreeId() . _DESC => 'Код услуги в деревер ЕРИП. Используется при генерации QR-кода',

    ConfigFieldsHutkigrosh::sandbox() => 'Sandbox',
    ConfigFieldsHutkigrosh::sandbox() . _DESC => 'Режим *песочницы*. Если включен, то все счета буду выставляться в тестовой системе trial.hgrosh.by',

    ConfigFieldsHutkigrosh::instructionsSection() => 'Секция "Инструкция"',
    ConfigFieldsHutkigrosh::instructionsSection() . _DESC => 'Если включена, то на итоговом экране клиенту будет доступна пошаговая инструкция по оплате счета в ЕРИП',

    ConfigFieldsHutkigrosh::qrcodeSection() => 'Секция QR-код',
    ConfigFieldsHutkigrosh::qrcodeSection() . _DESC => 'Если включена, то на итоговом экране клиенту будет доступна оплата счета по QR-коду',

    ConfigFieldsHutkigrosh::alfaclickSection() => 'Секция Alfaclick',
    ConfigFieldsHutkigrosh::alfaclickSection() . _DESC => 'Если включена, то на итоговом экране клиенту отобразится кнопка для выставления счета в Alfaclick',

    ConfigFieldsHutkigrosh::webpaySection() => 'Секция Webpay',
    ConfigFieldsHutkigrosh::webpaySection() . _DESC => 'Если включена, то на итоговом экране клиенту отобразится кнопка для оплаты счета картой (переход на Webpay)',

    ConfigFieldsHutkigrosh::notificationEmail() => 'Email оповещение',
    ConfigFieldsHutkigrosh::notificationEmail() . _DESC => 'Если включено, то шлюз ХуткiГрош будет отправлять email оповещение клиенту о выставлении счета',

    ConfigFieldsHutkigrosh::notificationSms() => 'Sms оповещение',
    ConfigFieldsHutkigrosh::notificationSms() . _DESC => 'Если включено, то шлюз ХуткiГрош будет отправлять sms оповещение клиенту о выставлении счета',

    ConfigFieldsHutkigrosh::completionText() => 'Текст успешного выставления счета',
    ConfigFieldsHutkigrosh::completionText() . _DESC => 'Текст, отображаемый кленту после успешного выставления счета. Может содержать html. ' .
        'В тексте допустимо ссылаться на переменные @order_id, @order_number, @order_total, @order_currency, @order_fullname, @order_phone, @order_address',
    ConfigFieldsHutkigrosh::completionText() . _DEFAULT => '<p>Счет №<strong>@order_number_or_id</strong> успешно выставлен в ЕРИП</p>
<p>Вы можете оплатить его наличными деньгами, пластиковой карточкой и электронными деньгами, в любом из отделений
    банков, кассах, банкоматах, платежных терминалах, в системе электронных денег, через Интернет-банкинг, М-банкинг,
    интернет-эквайринг</p>',

    ConfigFieldsHutkigrosh::completionCssFile() => 'CSS файл для итогового экрана',
    ConfigFieldsHutkigrosh::completionCssFile() . _DESC => 'Позволяет задать путь к CSS файлу для экрана успешного выставления счета',

    ConfigFieldsHutkigrosh::paymentMethodName() => 'Название способы оплаты',
    ConfigFieldsHutkigrosh::paymentMethodName() . _DESC => 'Название, отображаемое клиенту, при выборе способа оплаты',
    ConfigFieldsHutkigrosh::paymentMethodName() . _DEFAULT => 'Через систему «Расчет» (ЕРИП)',

    ConfigFieldsHutkigrosh::paymentMethodDetails() => 'Описание способа оплаты',
    ConfigFieldsHutkigrosh::paymentMethodDetails() . _DESC => 'Описание, отображаемое клиенту, при выборе способа оплаты',
    ConfigFieldsHutkigrosh::paymentMethodDetails() . _DEFAULT => '«Хуткi Грош»™ — платежный сервис по выставлению счетов в АИС *Расчет* (ЕРИП). ' .
        'После выставления счета Вам будет доступна его оплата пластиковой карточкой и электронными деньгами, в любом из отделений банков, кассах, банкоматах, платежных терминалах, в системе электронных денег, через Интернет-банкинг, М-банкинг, интернет-эквайринг',

    ConfigFieldsHutkigrosh::paymentMethodNameWebpay() => 'Название способы оплаты webpay',
    ConfigFieldsHutkigrosh::paymentMethodNameWebpay() . _DESC => 'Название, отображаемое клиенту, при выборе способа оплаты',
    ConfigFieldsHutkigrosh::paymentMethodNameWebpay() . _DEFAULT => 'Оплата картой',

    ConfigFieldsHutkigrosh::paymentMethodDetailsWebpay() => 'Описание способа оплаты webpay',
    ConfigFieldsHutkigrosh::paymentMethodDetailsWebpay() . _DESC => 'Описание, отображаемое клиенту, при выборе способа оплаты',
    ConfigFieldsHutkigrosh::paymentMethodDetailsWebpay() . _DEFAULT => 'Оплата заказа картой VISA/MasterCard/Белкарт через сервис WebPay',

    ConfigFieldsHutkigrosh::orderStatusPending() => 'Статус при выставлении счета',
    ConfigFieldsHutkigrosh::orderStatusPending() . _DESC => 'Какой статус выставить заказу при успешном выставлении счета в ЕРИП (идентификатор существующего статуса)',

    ConfigFieldsHutkigrosh::orderStatusPayed() => 'Статус при успешной оплате счета',
    ConfigFieldsHutkigrosh::orderStatusPayed() . _DESC => 'Какой статус выставить заказу при успешной оплате выставленного счета (идентификатор существующего статуса)',

    ConfigFieldsHutkigrosh::orderStatusFailed() => 'Статус при ошибке оплаты счета',
    ConfigFieldsHutkigrosh::orderStatusFailed() . _DESC => 'Какой статус выставить заказу при ошибке выставленния счета (идентификатор существующего статуса)',

    ConfigFieldsHutkigrosh::orderStatusCanceled() => 'Статус при отмене оплаты счета',
    ConfigFieldsHutkigrosh::orderStatusCanceled() . _DESC => 'Какой статус выставить заказу при отмене оплаты счета (идентификатор существующего статуса)',

    ConfigFieldsHutkigrosh::dueInterval() => 'Срок действия счета (дней)',
    ConfigFieldsHutkigrosh::dueInterval() . _DESC => 'Как долго счет, будет доступен в ЕРИП для оплаты',

    ConfigFieldsHutkigrosh::eripPath() => 'Путь в дереве ЕРИП',
    ConfigFieldsHutkigrosh::eripPath() . _DESC => 'По какому пути клиент должен искать выставленный счет',

    ClientViewFieldsHutkigrosh::INSTRUCTIONS_TAB_LABEL => 'Инструкция по оплате счета в ЕРИП',
    ClientViewFieldsHutkigrosh::INSTRUCTIONS => '<p>Для оплаты счета в ЕРИП необходимо:</p>
<ol>
    <li>Выбрать дерево платежей ЕРИП</li>
    <li>Выбрать услугу: <strong>@erip_path</strong></li>
    <li>Ввести номер счета: <strong>@order_number_or_id</strong></li>
    <li>Проверить корректность информации</li>
    <li>Совершить платеж.</li>
</ol>',


    ClientViewFieldsHutkigrosh::QRCODE_TAB_LABEL => 'Оплата по QR-коду',
    ClientViewFieldsHutkigrosh::QRCODE_DETAILS => '<p>Оплатить счет через банковское мобильное приложение по QR-коду:</p><div align="center">@qr_code</div><p>Информация о мобильных приложениях, поддерживающих сервис оплаты по QR-коду (платёжной ссылке), <a href="http://pay.raschet.by/" target="_blank"
style="color: #8c2003;"><span>здесь</span></a></p>',

    ClientViewFieldsHutkigrosh::ALFACLICK_TAB_LABEL => 'Выставить счет в «Альфа-Клик»',
    ClientViewFieldsHutkigrosh::ALFACLICK_DETAILS => 'Вы можете выставить счет в систему «Альфа-Клик». После этого он будет отражен в интернет-банке в пункте меню «Оплата счетов». Укажите свой номер телефона для выставления счета. ',
    ClientViewFieldsHutkigrosh::ALFACLICK_BUTTON_LABEL => 'Выставить счет',
    ClientViewFieldsHutkigrosh::ALFACLICK_MSG_SUCCESS => 'Счет успешно выставлен в систему «Альфа-Клик»',
    ClientViewFieldsHutkigrosh::ALFACLICK_MSG_UNSUCCESS => 'Ошибка выставления счета в систему «Альфа-Клик»',

    ClientViewFieldsHutkigrosh::WEBPAY_TAB_LABEL => 'Оплатить картой',
    ClientViewFieldsHutkigrosh::WEBPAY_DETAILS => 'Вы можете оплатить счет с помощью карты Visa, Mastercard или Белкарт через систему электронных платежей WEBPAY',
    ClientViewFieldsHutkigrosh::WEBPAY_BUTTON_LABEL => 'Перейти к оплате',
    ClientViewFieldsHutkigrosh::WEBPAY_MSG_SUCCESS => 'Счет успешно оплачен через сервис WebPay',
    ClientViewFieldsHutkigrosh::WEBPAY_MSG_UNSUCCESS => 'Ошибка оплаты счета через сервис WebPay',
    ClientViewFieldsHutkigrosh::WEBPAY_MSG_UNAVAILABLE => 'Извините, операция временно недоступна',

    AdminViewFields::ADMIN_PAYMENT_METHOD_NAME => 'ХуткiГрош',
    AdminViewFields::ADMIN_PAYMENT_METHOD_DESCRIPTION => '«Хуткi Грош»™ — многофункциональный сервис по подключению к ЕРИП (cистеме платежей АИС «Расчет») для расчета и приема платежей за товары и услуги по всей Беларуси. «Хуткi Грош»™ — один из крупнейших агрегаторов оплат НКФО "ЕРИП".',

    ClientViewFieldsHutkigrosh::HUTKIGROSH_ABOUT_REGISTRATION_DATA => 'Общество с ограниченной ответственностью "Электронные системы и сервисы" (www.esas.by)',
    ClientViewFieldsHutkigrosh::HUTKIGROSH_ABOUT_FULL_NAME => 'Свидетельство о гос. регистрации с регистрационным номером 191358568 от 26.08.2010, выданное Минским горисполкомом. (УНП: 191358568)',
    ClientViewFieldsHutkigrosh::HUTKIGROSH_ADDRESS_LEGAL => 'Юридический адрес: 220073, г. Минск, ул. Скрыганова, 6-316',
    ClientViewFieldsHutkigrosh::HUTKIGROSH_ADDRESS_POST => 'Почтовый адрес: ул. Немига, 40, подъезд 1, оф. 501 г. Минск, 220004 Беларусь.',

    ClientViewFields::COMPLETION_PAGE_TITLE => 'Оплата заказ в ЕРИП'
);