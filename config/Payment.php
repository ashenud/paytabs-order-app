<?php

require_once __DIR__ . "/Database.php";

class Payment extends Database
{
    protected string $table = 'payments';

    public const STATUS_INITIATED = 'initiated';

    public function initiate(int $orderId, string $paymentRequestJson): int
    {
        $stmt = $this->connection->prepare("
            INSERT INTO {$this->table} (order_id, status, payment_request)
            VALUES (?, ?, ?)
        ");

        $status = self::STATUS_INITIATED;

        $stmt->bind_param("iss", $orderId, $status,  $paymentRequestJson);
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
}
