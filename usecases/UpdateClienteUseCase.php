<?php
/**
 * Caso de uso para atualizar cliente
 * Contém a lógica de negócio para atualização de clientes
 */

require_once __DIR__ . '/../repositories/ClienteRepositoryInterface.php';

class UpdateClienteUseCase {
    private ClienteRepositoryInterface $clienteRepository;

    public function __construct(ClienteRepositoryInterface $clienteRepository) {
        $this->clienteRepository = $clienteRepository;
    }

    public function execute(int $id, array $data): array {
        // Verificar se cliente existe
        $existingCliente = $this->clienteRepository->findById($id);
        if (!$existingCliente) {
            return [
                'success' => false,
                'message' => 'Cliente não encontrado',
                'code' => 404
            ];
        }

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

        // Verificar se email já existe (excluindo o cliente atual)
        if ($this->clienteRepository->emailExists($data['email'], $id)) {
            return [
                'success' => false,
                'message' => 'Este email já está cadastrado no sistema',
                'code' => 409
            ];
        }

        // Sanitizar dados
        $cleanData = $this->sanitizeData($data);

        try {
            $updated = $this->clienteRepository->update($id, $cleanData);
            
            if ($updated) {
                return [
                    'success' => true,
                    'message' => 'Cliente atualizado com sucesso',
                    'code' => 200
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Nenhuma alteração foi feita',
                    'code' => 400
                ];
            }
        } catch (Exception $e) {
            error_log("Erro ao atualizar cliente: " . $e->getMessage());
            
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