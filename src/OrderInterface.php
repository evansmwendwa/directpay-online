<?php

namespace Evans\Directpay;

use \DateTimeInterface;

/**
 * Order Interface
 *
 * @author Evans Mwendwa
 */
interface OrderInterface
{
    public function getAmount(): float;

    public function getCurrency(): string;

    public function getEmail(): string;

    public function getFirstname(): string;

    public function getLastname(): string;

    public function getOrderNumber(): string;

    public function getPhoneNumber(): string;

    public function getOrderDate(): DateTimeInterface;

    public function getServiceType(): int;

    public function getPaymentDescription(): string;

    public function setTransactionToken($token);

    public function setTransactionReference($reference);
}
