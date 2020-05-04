<?php
/**
 * Created by IntelliJ IDEA.
 * User: nikit
 * Date: 26.02.2019
 * Time: 12:49
 */

namespace esas\cmsgate\hutkigrosh\utils;


use esas\cmsgate\utils\RequestParams;

class RequestParamsHutkigrosh extends RequestParams
{
    const BILL_ID = "bill_id";
    const PHONE = "phone";
    const PURCHASE_ID = "purchaseid"; // не менять, т.к. приходит с ХГ
    const ORDER_NUMBER = "order_number";
    const ORDER_ID = "order_id";
    const WEBPAY_STATUS = "webpay_status";
    const HUTKIGROSH_STATUS = "hutkigrosh_status";
}