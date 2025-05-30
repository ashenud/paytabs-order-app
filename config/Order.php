<?php
require_once __DIR__ . "/Database.php";

class Order extends Database
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_PAID = 'paid';
    public const STATUS_FAILED = 'failed';
    public const STATUS_REFUNDED = 'refunded';

    public const SHIPPING_METHOD_SHIPPING = 'shipping';
    public const SHIPPING_METHOD_PICKUP = 'pickup';

    public function getAll(): array
    {
        $stmt = $this->connection->prepare("SELECT * FROM orders ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getById($id): bool|array|null
    {
        $stmt = $this->connection->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function create($status = self::STATUS_PENDING, $shippingMethod = self::SHIPPING_METHOD_SHIPPING): int|bool
    {
        $stmt = $this->connection->prepare("INSERT INTO orders (status, shipping_method) VALUES (?, ?)");
        $stmt->bind_param("ss", $status, $shippingMethod);
        return $stmt->execute() ? $this->connection->insert_id : false;
    }

    public function addProduct($orderId, $productId, $quantity): bool
    {
        $stmt = $this->connection->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $orderId, $productId, $quantity);
        return $stmt->execute();
    }
}
