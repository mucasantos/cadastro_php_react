<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../models/Produto.php';

$database = new Database();
$db = $database->getConnection();

$produto = new Produto($db);

$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->cliente_id) &&
    !empty($data->produto_id)
) {
    if ($produto->dissociateFromClient($data->cliente_id, $data->produto_id)) {
        http_response_code(200);
        echo json_encode(array("message" => "Produto desassociado do cliente com sucesso."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Não foi possível desassociar o produto do cliente."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "ID do cliente e ID do produto são obrigatórios."));
}
?>