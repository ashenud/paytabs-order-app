<?php
require_once __DIR__ . "/Database.php";

class Product extends Database
{
    public function getAll(): array
    {
        $stmt = $this->connection->prepare("SELECT * FROM products");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function find($id): bool|array|null
    {
        $stmt = $this->connection->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
