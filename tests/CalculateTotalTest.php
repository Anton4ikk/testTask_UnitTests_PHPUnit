<?php # /tests/CalculateTotalTest.php

use PHPUnit\Framework\TestCase;

require 'scr/OrderManager.php';

class CalculateTotalTest extends TestCase
{
    public function testWithEmptyArray(): void
    {
        $reflection = new ReflectionClass('OrderManager');
        $orderManager = $reflection->newInstanceWithoutConstructor();
        $items = [];

        $total = $orderManager->calculateTotal($items);

        $this->assertEquals(0, $total);
    }

    public function testWithNormalArray(): void
    {
        $reflection = new ReflectionClass('OrderManager');
        $orderManager = $reflection->newInstanceWithoutConstructor();
        $items = [
            [
                'id' => 1,
                'price' => 10.5,
                'quantity' => 1,
            ],
            [
                'id' => 2,
                'price' => 10.0,
                'quantity' => 2,
            ],
        ];

        $total = $orderManager->calculateTotal($items);

        $this->assertEquals(30.5, $total);
    }

    public function testWithZeroPriceOrQuantity(): void
    {
        $reflection = new ReflectionClass('OrderManager');
        $orderManager = $reflection->newInstanceWithoutConstructor();
        $items = [
            [
                'id' => 1,
                'price' => 0,
                'quantity' => 2,
            ],
            [
                'id' => 2,
                'price' => 10.0,
                'quantity' => 0,
            ],
            [
                'id' => 3,
                'price' => 15.0,
                'quantity' => 1,
            ],
        ];

        $total = $orderManager->calculateTotal($items);

        $this->assertEquals(15.0, $total);
    }
}