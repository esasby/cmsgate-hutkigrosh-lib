<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 16.02.2018
 * Time: 12:49
 */

namespace esas\cmsgate\hutkigrosh\protocol;


use esas\cmsgate\protocol\Amount;

class HutkigroshBillInfoRs extends HutkigroshRs
{
    private $billId;
    private $eripId;
    private $orderId;
    private $fullName;
    private $mobilePhone;
    private $email;
    private $fullAddress;
    /**
     * @var Amount
     */
    private $amount;
    private $products;
    private $status;

    /**
     * @return string
     */
    public function getBillId()
    {
        return $this->billId;
    }

    /**
     * @param string $billId
     */
    public function setBillId($billId)
    {
        $this->billId = trim($billId);
    }

    /**
     * @return mixed
     */
    public function getEripId()
    {
        return $this->eripId;
    }

    /**
     * @param mixed $eripId
     */
    public function setEripId($eripId)
    {
        $this->eripId = trim($eripId);
    }

    /**
     * @deprecated использовать getOrderId
     * @return mixed
     */
    public function getInvId()
    {
        return $this->getOrderId();
    }

    /**
     * @deprecated использовать setOrderId
     * @param mixed $invId
     */
    public function setInvId($invId)
    {
        $this->setOrderId($invId);
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param mixed $orderId
     * @return HutkigroshBillInfoRs
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param mixed $fullName
     */
    public function setFullName($fullName)
    {
        $this->fullName = self::arraySafe($fullName);
    }

    /**
     * @return mixed
     */
    public function getMobilePhone()
    {
        return $this->mobilePhone;
    }

    /**
     * @param mixed $mobilePhone
     */
    public function setMobilePhone($mobilePhone)
    {
        $this->mobilePhone = self::arraySafe($mobilePhone);
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = self::arraySafe($email);
    }

    /**
     * @return mixed
     */
    public function getFullAddress()
    {
        return $this->fullAddress;
    }

    /**
     * @param mixed $fullAddress
     */
    public function setFullAddress($fullAddress)
    {
        $this->fullAddress = self::arraySafe($fullAddress);
    }

    /**
     * @return Amount
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param Amount $amount
     */
    public function setAmount(Amount $amount)
    {
        $this->amount = $amount;
    }


    /**
     * @return mixed
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param mixed $products
     */
    public function setProducts($products)
    {
        $this->products = $products;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = trim($status);
    }

    public function isStatusPayed()
    {
        return $this->status == 'Payed';
    }

    public function isStatusCanceled()
    {
        return in_array($this->status, array('Outstending', 'DeletedByUser', 'PaymentCancelled'));
    }

    public function isStatusPending()
    {
        return in_array($this->status, array('PaymentPending', 'NotSet'));
    }
}