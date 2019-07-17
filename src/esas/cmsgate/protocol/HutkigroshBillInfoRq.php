<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 28.02.2018
 * Time: 12:28
 */

namespace esas\cmsgate\protocol;


class HutkigroshBillInfoRq extends HutkigroshRq
{
    private $billId;

    /**
     * HutkigroshBillInfoRq constructor.
     * @param $billId
     */
    public function __construct($billId)
    {
        $this->billId = $billId;
    }


    /**
     * @return mixed
     */
    public function getBillId()
    {
        return $this->billId;
    }

    /**
     * @param mixed $billId
     */
    public function setBillId($billId)
    {
        $this->billId = $billId;
    }

}