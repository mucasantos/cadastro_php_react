<?php
/**
 * Caso de uso para criar cliente
 * Contém a lógica de negócio para criação de clientes
 */

require_once __DIR__ . '/../repositories/ClienteRepositoryInterface.php';

class CreateClienteUseCase {
    private ClienteRepositoryInterface $clienteRepository;

    public function __construct(ClienteRepositoryInterface $clienteRepository) {
        $this->clienteRepository = $clienteRepository;
    }

    public function execute(array $data): array {
        // Validação de dados obrigatórios
        if (empty($data['nome']) || empty($data['email'])) {
            return [
                'success' => false,
                'message' => 'Nome e email são obrigatórios',
                'code' => 400
            ];
        }

        // Validação de email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'message' => 'Email inválido',
                'code' => 400
            ];
        }

        // Verificar se email já existe
        if ($this->clienteRepository->emailExists($data['email'])) {
            return [
                'success' => false,
                'message' => 'Este email já está cadastrado no sistema',
                'code' => 409
            ];
        }

        // Sanitizar dados
        $cleanData = $this->sanitizeData($data);

        try {
            $id = $this->clienteRepository->create($cleanData);
            
            return [
                'success' => true,
                'message' => 'Cliente criado com sucesso',
                'data' => ['id' => $id],
                'code' => 201
            ];
        } catch (Exception $e) {
            error_log("Erro ao criar cliente: " . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'Erro interno do servidor',
                'code' => 500
            ];
        }
    }

    private function sanitizeData(array $data): array {
        return [
            'nome' => trim(strip_tags($data['nome'])),
            'email' => trim(strtolower(strip_tags($data['email']))),
            'telefone' => isset($data['telefone']) ? trim(strip_tags($data['telefone'])) : null,
            'endereco' => isset($data['endereco']) ? trim(strip_tags($data['endereco'])) : null,
            'data_nascimento' => isset($data['data_nascimento']) && !empty($data['data_nascimento']) 
                ? $data['data_nascimento'] : null,
            'status' => $data['status'] ?? 'ativo'
        ];
    }
}
?>