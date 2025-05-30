<?php
require_once __DIR__ . "/Database.php";

class Order extends Database
{
    protected string $table = 'orders';

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
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table} WHERE id = ?");
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
        $stmt = $this->connection->prepare("INSERT INTO order_products (order_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $orderId, $productId, $quantity);
        return $stmt->execute();
    }

    public function getOrderProducts(int $orderId): array
    {
        $stmt = $this->connection->prepare("
            SELECT 
                op.id as order_product_id,
                op.product_id,
                p.name as product_name,
                p.price as product_price,
                op.quantity 
            FROM order_products op
            INNER JOIN products p ON p.id = op.product_id
            WHERE op.order_id = ?
        ");

        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
