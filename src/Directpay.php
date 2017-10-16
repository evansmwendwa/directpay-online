<?php

namespace Evans\Directpay;

use Evans\Directpay\OrderInterface;
use Evans\Directpay\XMLHelper;
use Evans\Directpay\Client;

/**
 *  Directpay Class
 *
 * @author Evans Mwendwa
 */
class Directpay {

    protected $companyToken;
    protected $acceptableCurrencies;
    protected $acceptablePaymentMethods;

    /**
     * Class Constructor
     *
     * @param string $companyToken
     * @param string $endpoint
     *
     * @return void
     */
    public function __construct($companyToken, $endpoint) {
        $this->companyToken = $companyToken;
        $this->endpoint = $endpoint;
        $this->acceptableCurrencies = ['USD', 'ZMW', 'TZS', 'KES', 'RWF', 'EUR', 'GBP', 'UGX'];
        $this->acceptablePaymentMethods = ['card', 'mpesa', 'airtel', 'mtn', 'tigo', 'vodacom'];
    }

    /**
     * create an Order
     *
     * @param Evans\Directpay\OrderInterface $Order
     *
     * @return void
     */
    public function createOrder(OrderInterface $order) {
        $post_xml = XMLHelper::createTransactionXML($order, $this->companyToken);

        $dpoResponse = Client::sendXMLRequest($this->endpoint, $post_xml);

        if (false === $dpoResponse) {
            return $this->errorResponse($order);
        }

        if (isset($dpoResponse->TransToken)) {
            $order->setTransactionToken($dpoResponse->TransToken);
        }

        if (isset($dpoResponse->TransRef)) {
            $order->setTransactionReference($dpoResponse->TransRef);
        }

        return $this->preparedResponse($dpoResponse, $order);
    }

    public function pay(OrderInterface $order) {
        $payment_method = $order->getPaymentMethod();
        if (!in_array($payment_method, $this->acceptablePaymentMethods)) {
            throw new Exception('Unsuported Payment Method');
        }

        if ($payment_method === 'card') {
            return $this->payWithCreditCard($order);
        } else {
            return $this->payWithMobileMoney($order);
        }
    }

    /**
     * pay with credit card
     *
     * @param Evans\Directpay\OrderInterface $Order
     *
     * @return void
     */
    protected function payWithCreditCard(OrderInterface $order) {
        $post_xml = XMLHelper::payWithCardXML($order, $this->companyToken);

        $dpoResponse = Client::sendXMLRequest($this->endpoint, $post_xml);

        if (false === $dpoResponse) {
            return $this->errorResponse($order);
        }

        return $this->preparedResponse($dpoResponse, $order);
    }

    /**
     * pay with mobile money
     *
     * @param Evans\Directpay\OrderInterface $Order
     *
     * @return void
     */
    protected function payWithMobileMoney(OrderInterface $order) {
        
    }

    /**
     * verifyPayment
     *
     * @param Evans\Directpay\OrderInterface $Order
     *
     * @return void
     */
    public function verifyPayment(OrderInterface $order) {
        
    }

    private function errorResponse($payload = []) {
        return [
            'status' => 'error',
            'code' => 400,
            'description' => 'Invalid merchant response',
            'payload' => $payload
        ];
    }

    private function preparedResponse($dpoResponse, $payload = []) {
        $status = 'error';

        if (isset($dpoResponse->Result) && $dpoResponse->Result === '000') {
            $status = 'success';
        }

        $response = [
            'status' => $status,
            'code' => '',
            'description' => '',
            'payload' => $payload
        ];

        if (isset($dpoResponse->Result)) {
            $response['code'] = $dpoResponse->Result;
        }

        if (isset($dpoResponse->ResultExplanation)) {
            $response['description'] = $dpoResponse->ResultExplanation;
        }

        return $response;
    }

}
