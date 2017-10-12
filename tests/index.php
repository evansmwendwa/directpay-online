<?php

namespace Evans\Directpay\Tests;

require __DIR__.'/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use Evans\Directpay\Directpay;
use Evans\Directpay\OrderInterface;
use \DateTimeInterface;

class Order implements OrderInterface {

    public function getAmount(): float {
        return 2000;
    }

    public function getCurrency(): string {
        return 'KES';
    }

    public function getEmail(): string {
        return 'evans@example.com';
    }

    public function getFirstname(): string {
        return 'John';
    }

    public function getLastname(): string {
        return 'Doe';
    }

    public function getOrderNumber(): string {
        return 'inv-1';
    }

    public function getPhoneNumber(): string {
        return '0725296847';
    }

    public function getOrderDate(): DateTimeInterface {
        return new \DateTime();
    }

    public function getServiceType(): int {
        return 6067;
    }

    public function getPaymentDescription(): string {
        return 'Paymenty for office space';
    }
}


$order = new Order();


$dpo = new Directpay(
    'D7E04568-FECD-4CDD-84BF-E5716FE77F62',
    'https://secure.sandbox.directpay.online/API/v5/'
);


/*
$dpo = new Directpay(
    'C1F73E52-0989-495D-AC7A-154B85434666',
    'https://secure.3gdirectpay.com/API/v5/'
);
*/

$newOrder = $dpo->createOrder($order);
echo $newOrder->ResultExplanation;
echo 'Tap';
