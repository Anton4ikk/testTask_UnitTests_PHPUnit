<?php # /tests/ProcessOrderTest.php

use PHPUnit\Framework\TestCase;

require 'scr/OrderManager.php';
require 'fixtures/OrderManagerMock.php';

class ProcessOrderTest extends TestCase
{
    public function testExceptionWhenOneItemIsNotInStock(): void
    {
        $paymentGateway = $this->createMock(PaymentGateway::class);
        $paymentGateway->method('charge')->willReturn(true);
        $stockService = $this->createMock(StockService::class);
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

        $this->expectExceptionMessage('Some items are out of stock');
        $isOrderProcessed = $orderManager->processOrder($items);
    }

    public function testFailedOrderCharge(): void
    {
        $paymentGateway = $this->createMock(PaymentGateway::class);
        $paymentGateway->method('charge')->willReturn(false);
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

        $isOrderProcessed = $orderManager->processOrder($items);
        $this->assertEquals(false, $isOrderProcessed);
    }

    public function testSuccessfulOrderCharge(): void
    {
        $paymentGateway = $this->createMock(PaymentGateway::class);
        $paymentGateway->method('charge')->willReturn(true);
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

        $isOrderProcessed = $orderManager->processOrder($items);
        $this->assertEquals(true, $isOrderProcessed);
    }
}