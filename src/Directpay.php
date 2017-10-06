<?php

namespace Evans\Directpay;

use Evans\Directpay\OrderInterface;
use Evans\Directpay\XMLHelper;

/**
 * Order Interface
 *
 * @author Evans Mwendwa
 */
class Directpay
{
    protected $apikey;

    /**
     * Class Constructor
     *
     * @param string $apikey
     *
     * @return void
     */
    public function __construct($apikey)
    {
        $this->apikey = $apikey;
    }

    /**
     * create an Order
     *
     * @param Evans\Directpay\OrderInterface $Order
     *
     * @return void
     */
    public function createOrder(OrderInterface $order)
    {

    }

    /**
     * pay with credit card
     *
     * @param Evans\Directpay\OrderInterface $Order
     *
     * @return void
     */
    public function payWithCreditCard(OrderInterface $order)
    {

    }

    /**
     * pay with mobile money
     *
     * @param Evans\Directpay\OrderInterface $Order
     *
     * @return void
     */
    public function payWithMobileMoney(OrderInterface $order)
    {

    }

    /**
     * verifyPayment
     *
     * @param Evans\Directpay\OrderInterface $Order
     *
     * @return void
     */
    public function verifyPayment(OrderInterface $order)
    {

    }
}
