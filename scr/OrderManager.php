<?php # /scr/OrderManager.php

class OrderManager
{
    private $stockService;
    private $paymentGateway;

    public function __construct(StockService $stockService, PaymentGateway $paymentGateway)
    {
        $this->stockService = $stockService;
        $this->paymentGateway = $paymentGateway;
    }

    /**
     * Рассчитывает общую стоимость заказа.
     *
     * @param array $items Формат: [['id' => int, 'price' => float, 'quantity' => int], ...]
     * @return float
     */
    public function calculateTotal(array $items): float
    {
        $total = 0;
        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    /**
     * Проверяет наличие всех товаров в заказе на складе.
     *
     * @param array $items Формат: [['id' => int, 'quantity' => int], ...]
     * @return bool
     */
    public function checkStock(array $items): bool
    {
        foreach ($items as $item) {
            if (!$this->stockService->isInStock($item['id'], $item['quantity'])) {
                return false;
            }
        }
        return true;
    }

    /**
     * Обрабатывает заказ, включая оплату.
     *
     * @param array $items Формат: [['id' => int, 'price' => float, 'quantity' => int], ...]
     * @return bool Успех или неудача
     * @throws Exception
     */
    public function processOrder(array $items): bool
    {
        if (!$this->checkStock($items)) {
            throw new Exception("Some items are out of stock");
        }

        $total = $this->calculateTotal($items);
        return $this->paymentGateway->charge($total);
    }
}