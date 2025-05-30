<?php

require_once __DIR__ . "/../config.php";

class Database
{
    private string $dbName = DATABASE_NAME;
    private string $dbUser = DATABASE_USER;
    private string $dbPass = DATABASE_PASS;
    private string $dbHost = DATABASE_HOST;

    protected mysqli $connection;

    public function __construct()
    {
        $this->connection = new mysqli($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName);
        $this->connection->set_charset("utf8");

        if ($this->connection->connect_error) {
            printf("Connection failed: %s\n", $this->connection->connect_error);
            exit();
        }
    }

    public function getConnection(): mysqli
    {
        return $this->connection;
    }
}
