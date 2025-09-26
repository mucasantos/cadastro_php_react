<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../models/Produto.php';

$database = new Database();
$db = $database->getConnection();

$produto = new Produto($db);

$produto->id = isset($_GET['id']) ? $_GET['id'] : die();

if ($produto->readOne()) {
    $produto_arr = array(
        "id" => $produto->id,
        "nome" => $produto->nome,
        "descricao" => $produto->descricao,
        "preco" => floatval($produto->preco),
        "status" => $produto->status,
        "data_criacao" => $produto->data_criacao,
        "data_atualizacao" => $produto->data_atualizacao
    );

    http_response_code(200);
    echo json_encode($produto_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Produto não encontrado."));
}
?>