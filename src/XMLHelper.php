<?php

namespace Evans\Directpay;

use Evans\Directpay\OrderInterface;

/**
 * XMLHelper
 */
class XMLHelper {

    public static function createTransactionXML(OrderInterface $order, $companyToken) {

        $post_xml = '<?xml version="1.0" encoding="utf-8"?>';
        $post_xml .= '<API3G>';
        $post_xml .= '<CompanyToken>' . $companyToken . '</CompanyToken>';
        $post_xml .= '<Request>createToken</Request>';
        $post_xml .= '<Transaction>';
        $post_xml .= '<PaymentAmount>' . $order->getAmount() . '</PaymentAmount>';
        $post_xml .= '<PaymentCurrency>' . $order->getCurrency() . '</PaymentCurrency>';
        $post_xml .= '<CompanyRefUnique>0</CompanyRefUnique>';
        $post_xml .= '<CompanyRef>'. $order->getInvoiceNumber() .'</CompanyRef>';
        $post_xml .= '<customerFirstName>' . $order->getFirstname() . '</customerFirstName>';
        $post_xml .= '<customerEmail>' . $order->getEmail() . '</customerEmail>';
        $post_xml .= '<customerLastName>' . $order->getLastname() . '</customerLastName>';
        $post_xml .= '</Transaction>';
        $post_xml .= '<Services>';
        $post_xml .= '<Service>';
        $post_xml .= '<ServiceType>' . $order->getServiceType() . '</ServiceType>';
        $post_xml .= '<ServiceDescription>' . $order->getPaymentDescription() . '</ServiceDescription>';
        $post_xml .= '<ServiceDate>' . $order->getOrderDate()->format('Y/m/d h:i') . '</ServiceDate>';
        $post_xml .= '</Service>';
        $post_xml .= '</Services>';
        $post_xml .= '</API3G>';

        return $post_xml;
    }

    public static function payWithCardXML(OrderInterface $params, $companyToken) {
        $post_xml = '<?xml version="1.0" encoding="utf-8"?>';
        $post_xml .= '<API3G>';
        $post_xml .= '<CompanyToken>' . $params['company_token'] . '</CompanyToken>';
        $post_xml .= '<Request>chargeTokenCreditCard</Request>';
        $post_xml .= '<TransactionToken>' . $params['transaction_token'] . '</TransactionToken>';
        $post_xml .= '<CreditCardNumber>' . $params['card_number'] . '</CreditCardNumber>';
        $post_xml .= '<CreditCardExpiry>' . $params['card_expiry']->format('my') . '</CreditCardExpiry>';
        $post_xml .= '<CreditCardCVV>' . $params['card_cvv'] . '</CreditCardCVV>';
        $post_xml .= '<CardHolderName>' . $params['card_holders_name'] . '</CardHolderName>';
        $post_xml .= '<CardHolderID>' . bin2hex(openssl_random_pseudo_bytes(8)) . '</CardHolderID>';
        $post_xml .= '</API3G>';

        return $post_xml;
    }

    public static function payWithMobile(OrderInterface $order, $companyToken) {
        $post_xml = self::beginXMLString($companyToken);
        $post_xml .= '<Request>ChargeTokenMobile</Request>';
        $post_xml .= '<TransactionToken>' . (string) $order->getTransactionToken() . '</TransactionToken>';
        $post_xml .= '<PhoneNumber>' . (string) $order->getPhoneNumber() . '</PhoneNumber>';
        $post_xml .= '<PaymentName>' . (string) strtolower($order->getPaymentMethod()) . '</PaymentName>';
        $post_xml .= self::endXMLString();
        return $post_xml;
    }

    public static function verifyTransactionXML(OrderInterface $order, $companyToken) {
        $post_xml = self::beginXMLString($companyToken);
        $post_xml .= '<Request>verifyToken</Request>';
        $post_xml .= '<TransactionToken>' . $order->getTransactionToken() . '</TransactionToken>';
        $post_xml .= self::endXMLString();
        return $post_xml;
    }

    public static function getMobileOptionsXML(OrderInterface $order, $companyToken) {
        $post_xml = self::beginXMLString($companyToken);
        $post_xml .= '<Request>GetMobilePaymentOptions</Request>';
        $post_xml .= '<TransactionToken>' . $order->getTransactionToken() . '</TransactionToken>';
        $post_xml .= self::endXMLString();
        return $post_xml;
    }

    protected static function beginXMLString($companyToken = '') {
        return '<?xml version="1.0" encoding="utf-8"?>'
                . '<API3G>'
                . '<CompanyToken>' . $companyToken . '</CompanyToken>';
    }

    protected static function endXMLString() {
        return '</API3G>';
    }

}
