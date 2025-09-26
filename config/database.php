<?php
class Database {
    private $host = "localhost";
    private $db_name = "crud_clientes";
    private $username = "root";
    private $password = "lipetoni";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Erro de conexão: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>