<?php

namespace Evans\Directpay;

use Evans\Directpay\OrderInterface;
use Evans\Directpay\XMLHelper;
use Evans\Directpay\Client;

/**
 * Order Interface
 *
 * @author Evans Mwendwa
 */
class Directpay
{
    protected $companyToken;
    protected $acceptableCurrencies;

    /**
     * Class Constructor
     *
     * @param string $apikey
     * @param string $endpoint
     *
     * @return void
     */
    public function __construct($companyToken, $endpoint)
    {
        $this->companyToken = $companyToken;
        $this->endpoint = $endpoint;
        $this->acceptableCurrencies = ['USD','ZMW','TZS','KES','RWF','EUR','GBP','UGX'];
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
        $post_xml = XMLHelper::createTransactionXML($order, $this->companyToken);

        //$response = Client::sendXMLRequest($this->endpoint, $post_xml);

        return Client::mockRequest($this->endpoint, $post_xml);
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
