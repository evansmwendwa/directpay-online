<?php

namespace Evans\Directpay;

/**
 * Order Interface
 *
 * @author Evans Mwendwa
 */
interface PayInterface {

    public function getAmount();

    public function getCurrency();

    public function getEmail();

    public function getFirstname();

    public function getLastname();

    public function getInvoiceNumber();

    public function getPhoneNumber();

    public function getOrderDate();

    public function getServiceType();

    public function getPaymentDescription();

    public function setTransactionToken($token);

    public function setTransactionReference($reference);

    public function getTransactionToken();

    public function getTransactionReference();
}
