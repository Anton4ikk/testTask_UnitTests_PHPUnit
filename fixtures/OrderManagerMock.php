<?php # /fixtures/OrderManagerMock.php

interface StockService
{
    public function isInStock(int $productId, int $quantity): bool;
}

interface PaymentGateway
{
    public function charge(float $amount): bool;
}
