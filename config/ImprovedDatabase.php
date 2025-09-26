<?php
/**
 * Implementação melhorada da classe Database
 * Seguindo princípios SOLID e Clean Architecture
 */

require_once 'DatabaseInterface.php';

class ImprovedDatabase implements DatabaseInterface {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn;
    private static $instance = null;

    private function __construct() {
        $this->host = $_ENV['DB_HOST'] ?? "localhost";
        $this->db_name = $_ENV['DB_NAME'] ?? "crud_clientes";
        $this->username = $_ENV['DB_USER'] ?? "root";
        $this->password = $_ENV['DB_PASS'] ?? "lipetoni";
    }

    // Singleton pattern para garantir uma única conexão
    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): ?PDO {
        if ($this->conn === null) {
            try {
                $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                ];

                $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            } catch (PDOException $exception) {
                error_log("Database connection error: " . $exception->getMessage());
                throw new Exception("Erro de conexão com o banco de dados");
            }
        }

        return $this->conn;
    }

    public function beginTransaction(): bool {
        return $this->getConnection()->beginTransaction();
    }

    public function commit(): bool {
        return $this->getConnection()->commit();
    }

    public function rollback(): bool {
        return $this->getConnection()->rollBack();
    }

    // Método para executar queries com prepared statements
    public function execute(string $query, array $params = []): PDOStatement {
        $stmt = $this->getConnection()->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }

    // Método para buscar um registro
    public function fetchOne(string $query, array $params = []): ?array {
        $stmt = $this->execute($query, $params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    // Método para buscar múltiplos registros
    public function fetchAll(string $query, array $params = []): array {
        $stmt = $this->execute($query, $params);
        return $stmt->fetchAll();
    }

    // Método para inserir e retornar o ID
    public function insert(string $query, array $params = []): int {
        $this->execute($query, $params);
        return (int) $this->getConnection()->lastInsertId();
    }

    // Método para update/delete e retornar número de linhas afetadas
    public function modify(string $query, array $params = []): int {
        $stmt = $this->execute($query, $params);
        return $stmt->rowCount();
    }

    // Prevenir clonagem
    private function __clone() {}

    // Prevenir deserialização
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
}
?>