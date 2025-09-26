<?php
/**
 * API melhorada para criar cliente
 * Usando Clean Architecture e princípios SOLID
 */

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Incluir dependências
require_once __DIR__ . '/../../config/ImprovedDatabase.php';
require_once __DIR__ . '/../../repositories/ClienteRepository.php';
require_once __DIR__ . '/../../usecases/CreateClienteUseCase.php';

try {
    // Verificar método HTTP
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['message' => 'Método não permitido']);
        exit;
    }

    // Obter dados da requisição
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(['message' => 'JSON inválido']);
        exit;
    }

    // Configurar dependências
    $database = ImprovedDatabase::getInstance();
    $clienteRepository = new ClienteRepository($database);
    $createClienteUseCase = new CreateClienteUseCase($clienteRepository);

    // Executar caso de uso
    $result = $createClienteUseCase->execute($data);

    // Retornar resposta
    http_response_code($result['code']);
    
    if ($result['success']) {
        echo json_encode([
            'message' => $result['message'],
            'data' => $result['data'] ?? null
        ]);
    } else {
        echo json_encode([
            'message' => $result['message']
        ]);
    }

} catch (Exception $e) {
    error_log("Erro na API create cliente: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'message' => 'Erro interno do servidor'
    ]);
}
?>