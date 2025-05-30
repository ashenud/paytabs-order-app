<?php

require_once __DIR__ . "/Database.php";

class Payment extends Database
{
    protected string $table = 'payments';

    public const STATUS_INITIATED = 'initiated';

    public function create($orderId, $status = self::STATUS_INITIATED, $request = null, $response = null): int
    {
        $stmt = $this->connection->prepare("
            INSERT INTO {$this->table} (order_id, status, payment_request, payment_response) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("isss", $orderId, $status, $request, $response);
        $stmt->execute();

        return $this->connection->insert_id;
    }

    public function updateStatus($id, $status, $response = null): bool
    {
        $stmt = $this->connection->prepare("
            UPDATE {$this->table}
            SET status = ?, payment_response = ?
            WHERE id = ?
        ");
        $stmt->bind_param("ssi", $status, $response, $id);

        return $stmt->execute();
    }

    public function getByOrderId($orderId): bool|array|null
    {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table} WHERE order_id = ?");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function getById($id): bool|array|null
    {
        $stmt = $this->connection->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function getAll(): array
    {
        $result = $this->connection->query("SELECT * FROM {$this->table} ORDER BY created_at DESC");

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function initiate(int $orderId, string $paymentRequestJson): int
    {
        $stmt = $this->connection->prepare("
            INSERT INTO {$this->table} (order_id, status, payment_request)
            VALUES (?, self::STATUS_INITIATED, ?)
        ");
        $stmt->bind_param("is", $orderId, $paymentRequestJson);
        $stmt->execute();

        return $this->connection->insert_id;
    }
}
