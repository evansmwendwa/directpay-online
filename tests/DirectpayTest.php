<?php
declare(strict_types=1);

namespace Evans\Directpay\Tests;

use PHPUnit\Framework\TestCase;
use Evans\Directpay\Directpay;

/**
 * @covers Directpay
 */
final class DirectpayTest extends TestCase
{
    public function testDirectpayInstance(): void {
        $class = new Directpay('xxxxxx');

        $this->assertInstanceOf(Directpay::class, $class);
    }

    public function testOrderinterface(): void {
        
    }
}
