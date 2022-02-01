<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 17.08.2018
 * Time: 11:09
 */

namespace esas\cmsgate\hutkigrosh\view\client;

use esas\cmsgate\view\client\ClientViewFields;

/**
 * Перечисление полей, доступных на странице успешного выставления счета
 * Class ViewFields
 * @package esas\hutkigrosh\view
 */
class ClientViewFieldsHutkigrosh extends ClientViewFields
{
    const INSTRUCTIONS_TAB_LABEL = 'hutkigrosh_instructions_tab_label';
    const INSTRUCTIONS = 'hutkigrosh_instructions_text';
    const QRCODE_TAB_LABEL = 'hutkigrosh_qrcode_tab_label';
    const QRCODE_DETAILS = 'hutkigrosh_qrcode_details';
    const ALFACLICK_TAB_LABEL = 'hutkigrosh_alfaclick_tab_label';
    const ALFACLICK_DETAILS = 'hutkigrosh_alfaclick_details';
    const ALFACLICK_BUTTON_LABEL = 'hutkigrosh_alfaclick_button_label';
    const ALFACLICK_MSG_SUCCESS = 'hutkigrosh_alfaclick_msg_success';
    const ALFACLICK_MSG_UNSUCCESS = 'hutkigrosh_alfaclick_msg_unsuccess';
    const WEBPAY_TAB_LABEL = 'hutkigrosh_webpay_tab_label';
    const WEBPAY_DETAILS = 'hutkigrosh_webpay_details';
    const WEBPAY_BUTTON_LABEL = 'hutkigrosh_webpay_button_label';
    const WEBPAY_MSG_SUCCESS = 'hutkigrosh_webpay_msg_success';
    const WEBPAY_MSG_UNSUCCESS = 'hutkigrosh_webpay_msg_unsuccess';
    const WEBPAY_MSG_UNAVAILABLE = 'hutkigrosh_webpay_msg_unavailable';
    const HUTKIGROSH_ABOUT_FULL_NAME = 'hutkigrosh_about_full_name';
    const HUTKIGROSH_ABOUT_REGISTRATION_DATA = 'hutkigrosh_about_registration_data';
    const HUTKIGROSH_ADDRESS_POST = 'hutkigrosh_address_post';
    const HUTKIGROSH_ADDRESS_LEGAL = 'hutkigrosh_address_legal';
    const COMPLETION_PAGE_HEADER_HUTKIGROSH = 'completion_page_header_hutkigrosh';
    const COMPLETION_PAGE_HEADER_DETAILS_HUTKIGROSH = 'completion_page_header_details_hutkigrosh';

}