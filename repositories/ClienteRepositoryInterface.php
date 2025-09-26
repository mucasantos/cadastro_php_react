<?php
/**
 * Interface do repositório de clientes
 * Define o contrato para operações de dados
 */

interface ClienteRepositoryInterface {
    public function findAll(): array;
    public function findById(int $id): ?array;
    public function findByEmail(string $email): ?array;
    public function create(array $data): int;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function search(string $term, ?string $status = null): array;
    public function findByStatus(string $status): array;
    public function emailExists(string $email, ?int $excludeId = null): bool;
}
?>