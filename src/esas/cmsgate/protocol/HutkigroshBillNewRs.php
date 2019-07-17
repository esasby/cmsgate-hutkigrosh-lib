<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 16.02.2018
 * Time: 15:38
 */

namespace esas\cmsgate\protocol;


class HutkigroshBillNewRs extends HutkigroshRs
{
    private $billId;

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
        $this->billId = trim($billId);
    }

}