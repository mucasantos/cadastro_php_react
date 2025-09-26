<?php
/**
 * Implementação do repositório de clientes
 * Responsável apenas pelo acesso aos dados
 */

require_once 'ClienteRepositoryInterface.php';
require_once __DIR__ . '/../config/ImprovedDatabase.php';

class ClienteRepository implements ClienteRepositoryInterface {
    private DatabaseInterface $database;
    private string $table = 'clientes';

    public function __construct(DatabaseInterface $database) {
        $this->database = $database;
    }

    public function findAll(): array {
        $query = "SELECT id, nome, email, telefone, endereco, data_nascimento, status, created_at, updated_at 
                  FROM {$this->table} 
                  ORDER BY created_at DESC";
        
        return $this->database->fetchAll($query);
    }

    public function findById(int $id): ?array {
        $query = "SELECT id, nome, email, telefone, endereco, data_nascimento, status, created_at, updated_at 
                  FROM {$this->table} 
                  WHERE id = ?";
        
        return $this->database->fetchOne($query, [$id]);
    }

    public function findByEmail(string $email): ?array {
        $query = "SELECT id, nome, email, telefone, endereco, data_nascimento, status, created_at, updated_at 
                  FROM {$this->table} 
                  WHERE email = ?";
        
        return $this->database->fetchOne($query, [$email]);
    }

    public function create(array $data): int {
        $query = "INSERT INTO {$this->table} 
                  (nome, email, telefone, endereco, data_nascimento, status) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['nome'],
            $data['email'],
            $data['telefone'] ?? null,
            $data['endereco'] ?? null,
            $data['data_nascimento'] ?? null,
            $data['status'] ?? 'ativo'
        ];

        return $this->database->insert($query, $params);
    }

    public function update(int $id, array $data): bool {
        $query = "UPDATE {$this->table} 
                  SET nome = ?, email = ?, telefone = ?, endereco = ?, data_nascimento = ?, status = ? 
                  WHERE id = ?";
        
        $params = [
            $data['nome'],
            $data['email'],
            $data['telefone'] ?? null,
            $data['endereco'] ?? null,
            $data['data_nascimento'] ?? null,
            $data['status'] ?? 'ativo',
            $id
        ];

        return $this->database->modify($query, $params) > 0;
    }

    public function delete(int $id): bool {
        $query = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->database->modify($query, [$id]) > 0;
    }

    public function search(string $term, ?string $status = null): array {
        $query = "SELECT id, nome, email, telefone, endereco, data_nascimento, status, created_at, updated_at 
                  FROM {$this->table} 
                  WHERE (nome LIKE ? OR email LIKE ?)";
        
        $params = ["%{$term}%", "%{$term}%"];

        if ($status) {
            $query .= " AND status = ?";
            $params[] = $status;
        }

        $query .= " ORDER BY created_at DESC";

        return $this->database->fetchAll($query, $params);
    }

    public function findByStatus(string $status): array {
        $query = "SELECT id, nome, email, telefone, endereco, data_nascimento, status, created_at, updated_at 
                  FROM {$this->table} 
                  WHERE status = ? 
                  ORDER BY created_at DESC";
        
        return $this->database->fetchAll($query, [$status]);
    }

    public function emailExists(string $email, ?int $excludeId = null): bool {
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE email = ?";
        $params = [$email];

        if ($excludeId) {
            $query .= " AND id != ?";
            $params[] = $excludeId;
        }

        $result = $this->database->fetchOne($query, $params);
        return $result['count'] > 0;
    }
}
?>