<?php # /tests/CheckStockTest.php

use PHPUnit\Framework\TestCase;

require 'scr/OrderManager.php';
require 'fixtures/OrderManagerMock.php';

class CheckStockTest extends TestCase
{
    public function testWhenAllItemsInStock(): void
    {
        $paymentGateway = $this->createMock(PaymentGateway::class);
        $stockService = $this->createMock(StockService::class);
        $stockService->method('isInStock')->willReturn(true);

        $orderManager = new OrderManager($stockService, $paymentGateway);
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

        $isInStock = $orderManager->checkStock($items);

        $this->assertEquals(true, $isInStock);
    }

    public function testWhenAllItemsAreNotInStock(): void
    {
        $paymentGateway = $this->createMock(PaymentGateway::class);
        $stockService = $this->createMock(StockService::class, ['isInStock' => false]);
        $stockService->method('isInStock')->willReturn(false);

        $orderManager = new OrderManager($stockService, $paymentGateway);
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

        $isInStock = $orderManager->checkStock($items);

        $this->assertEquals(false, $isInStock);
    }

    public function testWhenOneItemIsNotInStock(): void
    {
        $paymentGateway = $this->createMock(PaymentGateway::class);
        $stockService = $this->createMock(StockService::class, ['isInStock' => false]);
        $stockService->method('isInStock')->willReturn(true, false);

        $orderManager = new OrderManager($stockService, $paymentGateway);
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

        $isInStock = $orderManager->checkStock($items);

        $this->assertEquals(false, $isInStock);
    }
}