<?php

namespace Evans\Directpay;

use Evans\Directpay\OrderInterface;
use Evans\Directpay\PayInterface;
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
    public function createOrder(OrderInterface $order)
    {
        if(!in_array($order->getCurrency(), $this->acceptableCurrencies)) {
            throw new \Exception('Unsupported currency value');
        }

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

    public function pay(PayInterface $payment) {
        $payment_method = $payment->getPaymentMethod();
        if (!in_array($payment_method, $this->acceptablePaymentMethods)) {
            throw new Exception('Unsuported Payment Method');
        }

        if ($payment_method === 'card') {
            return $this->payWithCreditCard($payment);
        } else {
            return $this->payWithMobileMoney($payment);
        }
    }

    /**
     * pay with credit card
     *
     * @param Evans\Directpay\OrderInterface $Order
     *
     * @return void
     */
    protected function payWithCreditCard(PayInterface $payment) {
        $post_xml = XMLHelper::payWithCardXML($payment, $this->companyToken);

        $dpoResponse = Client::sendXMLRequest($this->endpoint, $post_xml);

        if (false === $dpoResponse) {
            return $this->errorResponse($payment);
        }

        return $this->preparedResponse($dpoResponse, $payment);
    }

    /**
     * pay with mobile money
     *
     * @param Evans\Directpay\OrderInterface $Order
     *
     * @return void
     */
    protected function payWithMobileMoney(PayInterface $payment) {

    }


    /**
     * get mobile payment options
     *
     * @param Evans\Directpay\OrderInterface $Order
     *
     * @return dpoResponse
     */
    public function getMobilePaymentOptions(OrderInterface $order) {
        $post_xml = XMLHelper::getMobileOptionsXML($order, $this->companyToken);

        $dpoResponse = Client::sendXMLRequest($this->endpoint, $post_xml);

        if (false === $dpoResponse) {
            return $this->errorResponse($order);
        }

        return $this->preparedResponse($dpoResponse, $order);
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

        // payment options object has different properties
        if(isset($dpoResponse->paymentoptions)) {
            $response['status'] = 'success';
            $response['paymentoptions'] = [];

            if(isset($dpoResponse->paymentoptions->mobileoption)
                && is_array($dpoResponse->paymentoptions->mobileoption)) {
                $response['paymentoptions'] = $dpoResponse->paymentoptions->mobileoption;
            }
        }

        if (isset($dpoResponse->Result)) {
            $response['code'] = $dpoResponse->Result;
        }

        if (isset($dpoResponse->ResultExplanation)) {
            $response['description'] = $dpoResponse->ResultExplanation;
        }

        return $response;
    }

}
