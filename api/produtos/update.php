<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../models/Produto.php';

$database = new Database();
$db = $database->getConnection();

$produto = new Produto($db);

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->id) &&
    !empty($data->nome) &&
    !empty($data->preco)
) {
    $produto->id = $data->id;
    $produto->nome = $data->nome;
    $produto->descricao = $data->descricao ?? '';
    $produto->preco = $data->preco;
    $produto->status = $data->status ?? 'ativo';

    if ($produto->update()) {
        http_response_code(200);
        echo json_encode(array("message" => "Produto atualizado com sucesso."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Não foi possível atualizar o produto."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Dados incompletos. ID, nome e preço são obrigatórios."));
}
?>