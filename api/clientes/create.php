<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../models/Cliente.php';

$database = new Database();
$db = $database->getConnection();

$cliente = new Cliente($db);

$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->nome) &&
    !empty($data->email)
){
    $cliente->nome = $data->nome;
    $cliente->email = $data->email;
    $cliente->telefone = $data->telefone ?? '';
    $cliente->endereco = $data->endereco ?? '';
    
    // Tratar data_nascimento vazia ou inválida
    if (!empty($data->data_nascimento) && $data->data_nascimento !== '') {
        $cliente->data_nascimento = $data->data_nascimento;
    } else {
        $cliente->data_nascimento = null;
    }
    
    $cliente->status = $data->status ?? 'ativo';

    if($cliente->create()){
        http_response_code(201);
        echo json_encode(array("message" => "Cliente criado com sucesso."));
    }
    else{
        // Verificar se o erro foi por email duplicado
        if($cliente->emailExists()) {
            http_response_code(409);
            echo json_encode(array("message" => "Este email já está cadastrado no sistema."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Não foi possível criar o cliente."));
        }
    }
}
else{
    http_response_code(400);
    echo json_encode(array("message" => "Dados incompletos. Nome e email são obrigatórios."));
}
?>