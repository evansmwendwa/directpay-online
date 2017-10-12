<?php

namespace Evans\Directpay;

use Evans\Directpay\OrderInterface;

/**
 * XMLHelper
 */
class XMLHelper
{
    public static function createTransactionXML(OrderInterface $order, $companyToken) {

        $post_xml = '<?xml version="1.0" encoding="utf-8"?>';
        $post_xml .= '<API3G>';
        $post_xml .= '<CompanyToken>' . $companyToken . '</CompanyToken>';
        $post_xml .= '<Request>createToken</Request>';
        $post_xml .= '<Transaction>';
        $post_xml .= '<PaymentAmount>' . $order->getAmount() . '</PaymentAmount>';
        $post_xml .= '<PaymentCurrency>' . $order->getCurrency() . '</PaymentCurrency>';
        $post_xml .= '<CompanyRefUnique>0</CompanyRefUnique>';
        $post_xml .= '<customerFirstName>' . $order->getFirstname(). '</customerFirstName>';
        $post_xml .= '<customerEmail>' . $order->getEmail() . '</customerEmail>';
        $post_xml .= '<customerLastName>' . $order->getLastname() . '</customerLastName>';
        $post_xml .= '</Transaction>';
        $post_xml .= '<Services>';
        $post_xml .= '<Service>';
        $post_xml .= '<ServiceType>'. $order->getServiceType().'</ServiceType>';
        $post_xml .= '<ServiceDescription>'.$order->getPaymentDescription() .'</ServiceDescription>';
        $post_xml .= '<ServiceDate>' . $order->getOrderDate()->format('Y/m/d h:i') . '</ServiceDate>';
        $post_xml .= '</Service>';
        $post_xml .= '</Services>';
        $post_xml .= '</API3G>';

        return $post_xml;
    }
}
